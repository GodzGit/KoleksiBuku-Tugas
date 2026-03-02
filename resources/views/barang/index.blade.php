<form method="POST" action="{{ route('barang.cetak') }}">
    @csrf

    <table id="datatable" class="table table-bordered">
        <thead>
            <tr>
                <th>Pilih</th>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>
                    <input type="checkbox" name="selected[]" value="{{ $item->id_barang }}">
                </td>
                <td>{{ $item->id_barang }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->harga }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <br>

    X: <input type="number" name="x" min="1" max="5" required>
    Y: <input type="number" name="y" min="1" max="8" required>

    <button type="submit">Cetak Label</button>
</form>