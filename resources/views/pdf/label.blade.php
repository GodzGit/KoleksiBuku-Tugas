<!DOCTYPE html>
<html>
<head>
    <style>
@page {
    size: A4 portrait;
    margin: 4mm 5mm;
}

body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

table {
    width: 200mm;              /* A4 - margin kiri kanan */
    table-layout: fixed;
    border-collapse: separate; /* penting */
    border-spacing: 3mm 3mm;
}

td {
    width: 38mm;
    height: 18mm;
    text-align: center;
    vertical-align: middle;
    padding: 1.5mm;
    box-sizing: border-box;
    outline: 1px solid #000; 
}

.barcode-wrapper {
    display: inline-block;
    width: 34mm;
    height: 12mm;
    text-align: center;
}

.barcode-wrapper img {
    width: 32mm;
    height: 7.5mm;
    object-fit: contain;
    display: block;
    margin: 0 auto 1mm auto;
}

.barcode-wrapper strong {
    font-size: 10px;
    display: block;
    margin-bottom: 0.5mm;
    line-height: 1;
}

.barcode-wrapper span {
    font-size: 9px;
    display: block;
    line-height: 1;
}
    </style>
</head>
<body>

@php
    $counter = 0;
@endphp

<table>
@for ($row = 1; $row <= 8; $row++)
    <tr>
        @for ($col = 1; $col <= 5; $col++)
            @php
                $current_slot = ($row - 1) * 5 + $col;
            @endphp

            <td>
            @if($current_slot >= $index_awal && $counter < count($barang))
                <div class="barcode-wrapper">
                    {{-- 🔥 BARCODE --}}
                    <img src="data:image/png;base64,{{ $barang[$counter]->barcode }}">

                    {{-- ID BARANG --}}
                    <strong>
                        {{ $barang[$counter]->kode_barang }}
                    </strong>

                    {{-- HARGA --}}
                    <span>
                        Rp {{ number_format($barang[$counter]->harga, 0, ',', '.') }}
                    </span>
                </div>

                @php $counter++; @endphp

            @endif
            </td>
        @endfor
    </tr>
@endfor
</table>

</body>
</html>