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
    padding: 2mm;
    box-sizing: border-box;
    /* outline: 1px solid #000;  */
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
                    <strong>{{ $barang[$counter]->nama_barang }}</strong><br>
                    Rp {{ number_format($barang[$counter]->harga,0,',','.') }}
                    @php $counter++; @endphp
                @endif
            </td>
        @endfor
    </tr>
@endfor
</table>

</body>
</html>