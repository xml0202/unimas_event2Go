<!DOCTYPE html>
<html>
<head>
    <title>QR Code Generator</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            
            margin: 0;
        }

        .content {
            text-align: center;
        }
    </style>
    <meta http-equiv="refresh" content="5">
</head>
<body>
    <div class="content">
        <h1>{{ $event->title }}</h1>
        <p>{{ $qrCode }}</p>
    </div>
</body>
</html>
