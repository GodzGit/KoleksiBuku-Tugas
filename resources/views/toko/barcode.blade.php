<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Barcode - {{ $toko->nama_toko }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .barcode-box {
            border: 1px solid #ccc;
            padding: 20px;
            display: inline-block;
            border-radius: 8px;
        }
        img {
            margin: 10px 0;
        }
        .info {
            margin-top: 10px;
            font-size: 14px;
        }
        .nama-toko {
            font-weight: bold;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="barcode-box">
        <div class="nama-toko">{{ $toko->nama_toko }}</div>
        <img src="data:image/png;base64,{{ $barcodeBase64 }}" alt="Barcode">
        <div class="info">{{ $toko->barcode }}</div>
    </div>
    <p><button onclick="window.print()" class="btn btn-primary">Cetak</button></p>
    <script>
        window.onload = function() {
            // Auto print
            window.print();
        };
    </script>
</body>
</html>