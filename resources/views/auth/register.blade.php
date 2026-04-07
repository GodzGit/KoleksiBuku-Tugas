@extends('layouts.auth')

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">

        <h4>New here?</h4>
        <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>

        <form class="pt-3" method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <div class="form-group">
                <input type="text"
                       class="form-control form-control-lg @error('name') is-invalid @enderror"
                       name="name"
                       placeholder="Name"
                       value="{{ old('name') }}"
                       required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <input type="email"
                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                       name="email"
                       placeholder="Email"
                       value="{{ old('email') }}"
                       required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <input type="password"
                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                       name="password"
                       placeholder="Password"
                       required>
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <input type="password"
                       class="form-control form-control-lg"
                       name="password_confirmation"
                       placeholder="Confirm Password"
                       required>
            </div>

            <!-- Pilihan Role -->
            <div class="form-group">
                <label class="font-weight-light">Register as</label>
                <div class="mt-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="roleCustomer" value="customer" @if(old('role', 'customer') == 'customer') checked @endif>
                        <label class="form-check-label" for="roleCustomer">Customer</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="roleVendor" value="vendor" @if(old('role') == 'vendor') checked @endif>
                        <label class="form-check-label" for="roleVendor">Vendor (Penjual)</label>
                    </div>
                </div>
            </div>

            <!-- Field Nama Vendor -->
            <div class="form-group" id="vendorField" @if(old('role') != 'vendor') style="display:none" @endif>
                <input type="text"
                       class="form-control form-control-lg @error('nama_vendor') is-invalid @enderror"
                       name="nama_vendor"
                       placeholder="Nama Vendor / Toko"
                       value="{{ old('nama_vendor') }}">
                <small class="text-muted">Masukkan nama toko atau kantin Anda</small>
                @error('nama_vendor') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-gradient-primary btn-lg">SIGN UP</button>
            </div>

            <div class="text-center mt-4 font-weight-light">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary">Login</a>
            </div>

        </form>

    </div>
</div>
@endsection

@section('js-page')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleCustomer = document.getElementById('roleCustomer');
        const roleVendor = document.getElementById('roleVendor');
        const vendorField = document.getElementById('vendorField');

        if (roleCustomer && roleVendor && vendorField) {
            function toggleVendorField() {
                vendorField.style.display = roleVendor.checked ? 'block' : 'none';
            }

            roleCustomer.addEventListener('change', toggleVendorField);
            roleVendor.addEventListener('change', toggleVendorField);
        }
    });
</script>
@endsection