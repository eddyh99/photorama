<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resizable & Draggable Areas</title>
</head>
<script src="<?=BASE_URL?>assets/js/qz-tray.js"></script>

<body>
<?php
/*$path = FCPATH . "assets/photobooth/TSM-1742817596/";
$input = $path . 'video.mp4';
$temp = $path . 'temp.mp4';
$ffmpeg = '/usr/bin/ffmpeg';

$command = "$ffmpeg -y -i \"{$input}\" -f lavfi -i anullsrc=channel_layout=stereo:sample_rate=44100 -shortest -vf \"scale=1024:768,format=yuv420p\" -c:v libx264 -profile:v baseline -level 3.0 -pix_fmt yuv420p -c:a aac -b:a 128k -crf 26 -preset fast -movflags +faststart \"{$temp}\" 2>&1";

exec($command, $output, $return_var);

echo "<pre>";
print_r($output);
echo "</pre>";

if ($return_var === 0) {
    echo "Video converted and replaced successfully.";
} else {
    echo "Conversion failed.";
}*/
?>


<script>
qz.security.setCertificatePromise(function(resolve, reject) {
   fetch("/assets/certs/digital-certificate.txt", {cache: 'no-store', headers: {'Content-Type': 'text/plain'}})
      .then(function(data) { data.ok ? resolve(data.text()) : reject(data.text()); });
});

qz.security.setSignatureAlgorithm("SHA512");
qz.security.setSignaturePromise(function(toSign) {
   return function(resolve, reject) {
       fetch('/home/sign', {
           method: 'POST',
           body: JSON.stringify({ data: toSign }),
           headers: { 'Content-Type': 'application/json' }
       })
       .then(response => {
           if (!response.ok) throw new Error("Signing request failed");
           return response.text();
       })
       .then(resolve)
       .catch(reject);
   };
});

qz.websocket.connect().then(() => {
    const printerName = "HP DJ 2130 series";  // Your printer name
    const config = qz.configs.create(printerName);

    const pdfUrl = "https://photoramabooth.com/public/test.pdf";

    return qz.print(config, [{ type: 'pdf', data: pdfUrl }]);
}).then(() => {
    console.log("Print job sent successfully!");
    qz.websocket.disconnect();
}).catch(err => {
    console.error("Print error: ", err);
    qz.websocket.disconnect();
});
</script>

</body>

</html>