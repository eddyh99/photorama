<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Mdl_pembayaran;
use Exception;
use SimpleSoftwareIO\QrCode\Generator;

class Payment extends BaseController
{

    private static $merchantId = "33173";
    private static $clientId = "BRN-0268-1695035015801";
    private static $secretKey = "SK-m8xe8SO4jVXAHngPu4By";

    public static function QRIS($price) {
        $merchantId = self::$merchantId;
        $clientId = self::$clientId;
        $secretKey = self::$secretKey;
        $timestamp = gmdate("Y-m-d\TH:i:s\Z");

        $stringToSign = $clientId . "|" . $timestamp;
        $siggy = self::getSignature($stringToSign);
        $token = self::gettoken($clientId, $timestamp, $siggy);

        $jsonResult = json_decode($token);
        $accessToken = $jsonResult->accessToken ?? null;
        
        $result = self::retriveQRIS($merchantId, $clientId, $accessToken, $timestamp, $secretKey, $price);
        
        $jsonObject = json_decode($result);
        $responseCode = $jsonObject->responseCode;


        if ($responseCode == "2004700") {
            $qrcode = new Generator;
            return (object) [
                "success" => true,
                "data" => (object) [
                    "tanggal" => $timestamp,
                    "invoice" => $jsonObject->partnerReferenceNo,
                    "qris"    => $qrcode->size(250)->generate($jsonObject->qrContent)
                ]
            ];
        }
        return (object) [
            "success" => false,
            "data" => null
        ];
    }

    static function retriveQRIS($merchantId, $clientId, $accessToken, $timestamp, $secretKey, $harga) {
        $systrace = rand(100000, 999999);
        
        $data = [
            "partnerReferenceNo" => $systrace,
            "amount" => [
                "value" => $harga,
                "currency" => "IDR"
            ],
            "merchantId" => $merchantId,
            "terminalId" => "A01",
            "additionalInfo" => [
                "postalCode" => "13120",
                "feeType" => "1"
            ]
        ];
        
        $postString = json_encode($data, JSON_UNESCAPED_SLASHES);
        
        $siggy = self::qris_signature($accessToken, $timestamp, $postString, $secretKey);
        
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

    static function qris_signature($accessToken, $timestamp, $body, $secretKey) {
        $sha256 = hash('sha256', $body);
        
        $signdata = "POST:/snap-adapter/b2b/v1.0/qr/qr-mpm-generate:" . $accessToken . ":" . strtolower($sha256) . ":" . $timestamp;
        
        return self::hmacSHA512($signdata, $secretKey);
    }

    static function paymentCheck_signature($accessToken, $timestamp, $body, $secretKey) {
        $sha256 = hash('sha256', $body);
        
        $signdata = "POST:/snap-adapter/b2b/v1.0/qr/qr-mpm-query:" . $accessToken . ":" . strtolower($sha256) . ":" . $timestamp;
        
        return self::hmacSHA512($signdata, $secretKey);
    }
    
    static function hmacSHA512($data, $key) {
        $hash = hash_hmac('sha512', $data, $key, true);
        return base64_encode($hash);
    }
    
    static function gettoken($clientId, $timestamp, $siggy) {
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
    
    static function getSignature($data) {
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

    public static function check() {
        header('Content-Type: application/json');
        $input = file_get_contents('php://input');
        if (empty($input)) {
            echo json_encode(['status' => 'error', 'message' => 'No input received']);
            return;
        }
    
        $result = json_decode($input);
        if ($result === null) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON format']);
            return;
        }

        if ($result->transactionStatusDesc == 'Success') {
            $pembayaran = new Mdl_pembayaran;
            $pembayaran->confirmPayment($result->originalReferenceNo);
            echo json_encode(['status' => 'success', 'message' => 'Payment confirmed']);
        };
    }

    // static function check($inv) {
    //     $merchantId = self::$merchantId;
    //     $clientId = self::$clientId;
    //     $secretKey = self::$secretKey;
    //     $systrace = rand(100000, 999999);
    //     $timestamp = gmdate("Y-m-d\TH:i:s\Z");

    //     $stringToSign = $clientId . "|" . $timestamp;
    //     $siggy = self::getSignature($stringToSign);
    //     $token = self::gettoken($clientId, $timestamp, $siggy);

    //     $jsonResult = json_decode($token);
    //     $accessToken = $jsonResult->accessToken ?? null;
    //     $url = DOKU_URL . "snap-adapter/b2b/v1.0/qr/qr-mpm-query";
        
    //     $data = [
    //         "originalReferenceNo" => $inv,
    //         "originalPartnerReferenceNo" => $inv,
    //         "serviceCode" => "51",
    //         "merchantId" => $merchantId
    //     ];
    //     $postString = json_encode($data, JSON_UNESCAPED_SLASHES);
    //     $siggy2 = self::paymentCheck_signature($accessToken, $timestamp, $postString, $secretKey);
        
    //     $headers = [
    //         "X-TIMESTAMP: $timestamp",
    //         "X-PARTNER-ID: $clientId",
    //         "X-SIGNATURE: $siggy2",
    //         "X-EXTERNAL-ID: $systrace",
    //         "Authorization: Bearer $accessToken",
    //         "Content-Type: application/json"
    //     ];
        
    //     $ch = curl_init($url);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
    //     $response = curl_exec($ch);
        
    //     if(curl_errno($ch)) {
    //         echo 'Curl error: ' . curl_error($ch);
    //     }
        
    //     curl_close($ch);
        
    //     $result = json_decode($response);
    //     if ($result->transactionStatusDesc === 'Success') {
    //         $pembayaran = new Mdl_pembayaran;
    //         $pembayaran->confirmPayment($inv);
    //     };
    // }
}
