<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verifikasi OTP</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #6a11cb, #9b5de5);
            font-family: Arial, sans-serif;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 350px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        .card h2 {
            margin-bottom: 10px;
            color: #6a11cb;
        }

        .subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .otp-input {
            width: 100%;
            padding: 12px;
            font-size: 20px;
            text-align: center;
            letter-spacing: 8px;
            border-radius: 8px;
            border: 2px solid #ccc;
            outline: none;
            transition: 0.3s;
        }

        .otp-input:focus {
            border-color: #6a11cb;
        }

        .btn {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            background: #6a11cb;
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #5311a8;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>Masukkan Kode OTP</h2>
    <p class="subtitle">Silakan masukkan 6 karakter kode yang dikirim ke email Anda.</p>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ url('/verify-otp') }}">
        @csrf
        <input type="text" name="otp" maxlength="6" required class="otp-input">
        <button type="submit" class="btn">Verifikasi</button>
    </form>
</div>

</body>
</html>