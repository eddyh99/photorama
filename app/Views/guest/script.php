<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resizable & Draggable Areas</title>
</head>
<script src="<?=BASE_URL?>assets/js/qz-tray.js"></script>

<body>
   
<script>
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