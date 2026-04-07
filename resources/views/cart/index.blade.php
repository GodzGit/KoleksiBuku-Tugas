@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">🛒 Keranjang Belanja</h4>
        
        @if(count($cart) > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Vendor</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $idmenu => $item)
                    <tr>
                        <td>{{ $item['nama_menu'] }}</td>
                        <td>{{ $item['nama_vendor'] }}</td>
                        <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('cart.update', $idmenu) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="jumlah" value="{{ $item['jumlah'] }}" min="1" style="width: 70px;" onchange="this.form.submit()">
                            </form>
                        </td>
                        <td>Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('cart.remove', $idmenu) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus item?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <th colspan="4" class="text-end">Total:</th>
                        <th colspan="2">Rp {{ number_format($total, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="d-flex justify-content-between mt-3">
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-secondary" onclick="return confirm('Kosongkan keranjang?')">
                    <i class="fas fa-trash-alt"></i> Kosongkan
                </button>
            </form>
            <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                <i class="fas fa-credit-card"></i> Lanjut ke Pembayaran
            </a>
        </div>
        @else
        <div class="alert alert-info text-center">
            Keranjang belanja kosong. <a href="{{ route('landing') }}">Belanja sekarang</a>
        </div>
        @endif
    </div>
</div>
@endsection