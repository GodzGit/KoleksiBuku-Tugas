<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans;
            margin: 40px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            border: 1px solid black;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>FAKULTAS Vokasi Universitas Airlangga</h2>
    <h3>LAPORAN DATA KOLEKSI BUKU</h3>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Judul</th>
            <th>Pengarang</th>
            <th>Kategori</th>
        </tr>
    </thead>
    <tbody>
        @foreach($buku as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->kode }}</td>
            <td>{{ $item->judul }}</td>
            <td>{{ $item->pengarang }}</td>
            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>Dicetak pada: {{ date('d M Y') }}</p>
</div>

</body>
</html>