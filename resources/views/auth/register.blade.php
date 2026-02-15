@extends('layouts.auth')

@section('content')
<div class="col-lg-4 mx-auto">
    <div class="auth-form-light text-left p-5">

        <h4>New here?</h4>
        <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>

        <form class="pt-3" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <input type="text"
                       class="form-control form-control-lg"
                       name="name"
                       placeholder="Name"
                       required>
            </div>

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

            <div class="mt-3 d-grid gap-2">
                <button type="submit"
                        class="btn btn-gradient-primary btn-lg">
                    SIGN UP
                </button>
            </div>

            <div class="text-center mt-4 font-weight-light">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary">
                    Login
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
