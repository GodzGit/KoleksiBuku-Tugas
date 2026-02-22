<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body {
    font-family: DejaVu Sans;
    text-align: center;
    margin: 0;
    padding: 0;
}

.container {
    border: 10px solid gold;
    margin: 40px;
    padding: 60px;
}

h1 {
    font-size: 40px;
}

h2 {
    margin-top: 30px;
}

.nama {
    font-size: 28px;
    font-weight: bold;
    margin: 20px 0;
}

.footer {
    margin-top: 60px;
}
</style>
</head>
<body>

<div class="container">
    <h1>SURAT KETERANGAN</h1>
    <h2>TERDAFTAR SEBAGAI KOLEKSI BUKU</h2>


    <p>Dengan ini menerangkan bahwa buku berikut telah terdaftar secara resmi dalam sistem Koleksi Buku.</p>
    <p>Diberikan kepada buku dengan judul:</p>

    <div class="nama">
        {{ $buku->judul }}
    </div>

    <p>
        Kategori: {{ $buku->kategori->nama_kategori ?? '-' }} <br>
        Pengarang: {{ $buku->pengarang }}
    </p>

    <!-- <div class="footer">
        <p>Ditetapkan pada {{ date('d M Y') }}</p>
    </div> -->
</div>

</body>
</html>