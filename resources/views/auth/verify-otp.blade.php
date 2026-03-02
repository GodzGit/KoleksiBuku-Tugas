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
    background: linear-gradient(180deg,rgba(255,255,255,1) 0%, rgba(166,109,173,1) 100%);
    font-family: 'Nunito', sans-serif;
}

.card {
    background: white;
    padding: 40px;
    border-radius: 15px;
    width: 380px;
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
    margin-bottom: 25px;
}

.otp-wrapper {
    display: flex;
    justify-content: space-between;
    margin-bottom: 20px;
}

.otp-box {
    width: 50px;
    height: 55px;
    text-align: center;
    font-size: 22px;
    border-radius: 8px;
    border: 2px solid #ccc;
    outline: none;
    transition: 0.3s;
}

.otp-box:focus {
    border-color: #6a11cb;
}

.btn {
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
    margin-bottom: 15px;
}
</style>
</head>
<body>

<div class="card">
    <h2>Masukkan Kode OTP</h2>
    <p class="subtitle">Masukkan 6 karakter kode yang dikirim ke email Anda.</p>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ url('/verify-otp') }}">
        @csrf

        <div class="otp-wrapper">
            <input type="text" maxlength="1" class="otp-box" required>
            <input type="text" maxlength="1" class="otp-box" required>
            <input type="text" maxlength="1" class="otp-box" required>
            <input type="text" maxlength="1" class="otp-box" required>
            <input type="text" maxlength="1" class="otp-box" required>
            <input type="text" maxlength="1" class="otp-box" required>
        </div>

        <!-- Hidden input buat kirim gabungan OTP -->
        <input type="hidden" name="otp" id="otp-value">

        <button type="submit" class="btn">Verifikasi</button>
    </form>
</div>

<script>
const inputs = document.querySelectorAll(".otp-box");
const hiddenInput = document.getElementById("otp-value");

inputs.forEach((input, index) => {

    input.addEventListener("input", () => {
        if (input.value.length === 1 && index < inputs.length - 1) {
            inputs[index + 1].focus();
        }
        updateOTP();
    });

    input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && input.value === "" && index > 0) {
            inputs[index - 1].focus();
        }
    });
});

function updateOTP() {
    let otp = "";
    inputs.forEach(input => {
        otp += input.value;
    });
    hiddenInput.value = otp;
}
</script>

</body>
</html>