@extends('layouts.auth')

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">

        <h4>Hello! let's get started</h4>
        <h6 class="font-weight-light">Sign in to continue.</h6>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form class="pt-3" method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <input type="email"
                       class="form-control form-control-lg"
                       name="email"
                       placeholder="Email"
                       required>
            </div>

            <div class="form-group">
                <input type="password"
                       class="form-control form-control-lg"
                       name="password"
                       placeholder="Password"
                       required>
            </div>

            <div class="mt-3">
                @if (Route::has('register'))
                    <a class="auth-link text-black" href="{{ route('register') }}">
                        {{ __('Don\'t have an account? Sign Up') }}
                    </a>
                @endif
            </div>

            <div class="mt-3 d-grid gap-2">
                <button type="submit" class="btn btn-gradient-primary btn-lg">
                    SIGN IN
                </button>
            </div>

        </form>

    </div>
</div>
@endsection
