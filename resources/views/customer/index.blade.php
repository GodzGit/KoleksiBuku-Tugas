@extends('layouts.master')

@section('title', 'Data Customer')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="card-title">📋 Data Customer</h4>
            <div>
                <a href="{{ route('customer.create-blob') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah (Blob)
                </a>
                <a href="{{ route('customer.create-file') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Tambah (File)
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td>{{ $customer->id_customer }}</td>
                        <td>{{ $customer->nama_customer }}</td>
                        <td>{{ $customer->email ?? '-' }}</td>
                        <td>
                            @if($customer->foto)
                                <img src="{{ route('customer.foto', $customer->id_customer) }}" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                            @elseif($customer->foto_path)
                                <img src="{{ asset('storage/' . $customer->foto_path) }}" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                            @else
                                <span class="text-muted">Tidak ada foto</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('customer.destroy', $customer->id_customer) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus customer ini?')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection