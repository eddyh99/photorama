<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Exception;
use SimpleSoftwareIO\QrCode\Generator;

class Payments extends BaseController
{

    public function index() {
        $merchantId = "MCH-1352-1634273860130";
        $clientId = "MCH-1352-1634273860130";
        $secretKey = "SK-KHUvvn4fm3zXRIip0UWY";
        $timestamp = gmdate("Y-m-d\TH:i:s\Z"); // Current UTC timestamp

        $stringToSign = $clientId . "|" . $timestamp;
        $siggy = $this->getSignature($stringToSign);
        $token = $this->gettoken($clientId, $timestamp, $siggy);

        $jsonResult = json_decode($token);
        $accessToken = $jsonResult->accessToken;
        
        $result = $this->retriveQRIS($merchantId, $clientId, $accessToken, $timestamp, $secretKey, 100000);
        
        $jsonObject = json_decode($result);
        dd($jsonObject);
        $responseCode = $jsonObject->responseCode;

        if ($responseCode == "2004700") {
            $qrisData = $jsonObject->qrContent;
            // Generate QR code image
            $qrcodePath = $this->generateQRCode($qrisData);
            
            // Display or handle the QR code image as required
            echo "QR Code generated at: " . $qrcodePath;
        }
    }

    public function generateQRCode($data) {
        $qrcode = new Generator;
        return $qrcode->size(120)->generate($data);
    }

    function retriveQRIS($merchantId, $clientId, $accessToken, $timestamp, $secretKey, $harga) {
        $systrace = rand(100000, 999999);
        
        $data = [
            "partnerReferenceNo" => $systrace,
            "amount" => [
                "value" => $harga,
                "currency" => "IDR"
            ],
            "feeAmount" => [
                "value" => "500.00",
                "currency" => "IDR"
            ],
            "merchantId" => $merchantId,
            "terminalId" => "A01",
            "additionalInfo" => [
                "postalCode" => "13120",
                "feeType" => "2"
            ]
        ];
        
        $postString = json_encode($data, JSON_UNESCAPED_SLASHES);
        
        $siggy = $this->qris_signature($accessToken, $timestamp, $postString, $secretKey);
        
        $url = DOKU_URL . "snap-adapter/b2b/v1.0/qr/qr-mpm-generate";
        
        $headers = [
            "X-TIMESTAMP: $timestamp",
            "X-PARTNER-ID: $clientId",
            "X-SIGNATURE: $siggy",
            "X-EXTERNAL-ID: $systrace",
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // dd($postString, $headers);
        
        $response = curl_exec($ch);
        
        if(curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        
        curl_close($ch);
        
        return $response;
    }

    function qris_signature($accessToken, $timestamp, $body, $secretKey) {
        $sha256 = hash('sha256', $body);
        
        $signdata = "POST:/snap-adapter/b2b/v1.0/qr/qr-mpm-generate:" . $accessToken . ":" . strtolower($sha256) . ":" . $timestamp;
        
        return $this->hmacSHA512($signdata, $secretKey);
    }
    
    function hmacSHA512($data, $key) {
        $hash = hash_hmac('sha512', $data, $key, true);
        return base64_encode($hash);
    }
    
    function gettoken($clientId, $timestamp, $siggy) {
        $url = DOKU_URL . "authorization/v1/access-token/b2b";
        
        $headers = [
            "Content-Type: application/json",
            "X-CLIENT-KEY: $clientId",
            "X-TIMESTAMP: $timestamp",
            "X-SIGNATURE: $siggy"
        ];
        
        $data = json_encode([
            "grantType" => "client_credentials"
        ]);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        $response = curl_exec($ch);
        
        if(curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }
        
        curl_close($ch);
        
        return $response;
    }
    
    function getSignature($data) {
        // Load the private key
        $privateKey = openssl_pkey_get_private(file_get_contents(FCPATH . 'assets/private-key.pem'));
        if ($privateKey === false) {
            throw new Exception("Invalid private key: " . openssl_error_string());
        }
        
        // Sign the data
        openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        
        // Return the signature in base64 format
        return base64_encode($signature);
    }
}
