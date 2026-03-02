<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        body {
            margin: 0;
            font-size: 10px;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        td {
            height: 21mm;
            vertical-align: top;
            padding: 3mm;
            box-sizing: border-box;

            /* HAPUS BORDER */
            /* border: 1px solid black; */

            /* GANTI JADI OUTLINE */
            outline: 0.5px solid #999;
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