@extends('layouts.master')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="card-title">📋 Daftar Transaksi</h4>
        
        {{-- FILTER --}}
        <form method="GET" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Vendor</label>
                    <select name="vendor" class="form-select">
                        <option value="">Semua Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->idvendor }}" {{ request('vendor') == $vendor->idvendor ? 'selected' : '' }}>
                                {{ $vendor->nama_vendor }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Lunas</option>
                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.transactions') }}" class="btn btn-secondary">
                        <i class="mdi mdi-refresh"></i> Reset
                    </a>
                </div>
            </div>
        </form>
        
        {{-- TABEL --}}
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pemesan</th>
                        <th>Vendor</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->idpesanan }}</td>
                        <td>{{ $transaction->nama }}</td>
                        <td>
                            @php
                                $vendorName = $transaction->detailPesanan->first()?->menu?->vendor?->nama_vendor ?? '-';
                            @endphp
                            {{ $vendorName }}
                        </td>
                        <td>{{ $transaction->timestamp->format('d/m/Y H:i') }}</td>
                        <td>Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td>
                            @if($transaction->status_bayar == 0)
                                <span class="badge bg-warning">Pending</span>
                            @elseif($transaction->status_bayar == 1)
                                <span class="badge bg-success">Lunas ✅</span>
                            @else
                                <span class="badge bg-danger">Batal</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.transactions.show', $transaction->idpesanan) }}" class="btn btn-sm btn-info">
                                <i class="mdi mdi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $transactions->withQueryString()->links() }}
    </div>
</div>
@endsection