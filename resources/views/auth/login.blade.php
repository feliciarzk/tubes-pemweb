<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SWASH</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

@php
    $startRegister = ($startRegister ?? false) || $errors->hasBag('register');
@endphp

<div class="auth-page">
    <div class="flip-wrap">
        <div class="flip-inner {{ $startRegister ? 'flipped' : '' }}" id="flipCard">

            <div class="auth-card face-front">
                <span class="brand-text">SWASH</span>
                <h1>Masuk ke akunmu</h1>
                <p class="auth-sub">Masuk dulu untuk memesan laundry dan memantau pesananmu.</p>

                @if ($errors->any() && !$errors->hasBag('register'))
                    <div class="auth-alert">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ url('/login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="login-email">Email</label>
                        <input type="email" id="login-email" name="email" class="form-control"
                               placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="login-password">Password</label>
                        <input type="password" id="login-password" name="password" class="form-control"
                               placeholder="Masukkan password" required>
                    </div>

                    <button type="submit" class="btn-swash">Masuk</button>
                </form>

                <p class="auth-foot">
                    Belum punya akun?
                    <button type="button" class="link-btn" onclick="flipCard(true)">Daftar sekarang</button>
                </p>
            </div>

            <div class="auth-card face-back">
                <span class="brand-text">SWASH</span>
                <h1>Buat akun baru</h1>
                <p class="auth-sub">Daftar dulu supaya bisa memesan laundry.</p>

                @if ($errors->hasBag('register'))
                    <div class="auth-alert">{{ $errors->getBag('register')->first() }}</div>
                @endif

                <form method="POST" action="{{ url('/register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="reg-name">Nama</label>
                        <input type="text" id="reg-name" name="name" class="form-control"
                               placeholder="Nama lengkap" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="reg-email">Email</label>
                        <input type="email" id="reg-email" name="email" class="form-control"
                               placeholder="nama@email.com" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="reg-password">Password</label>
                        <input type="password" id="reg-password" name="password" class="form-control"
                               placeholder="Minimal 6 karakter" required>
                    </div>

                    <div class="mb-3">
                        <label for="reg-password2">Konfirmasi password</label>
                        <input type="password" id="reg-password2" name="password_confirmation" class="form-control"
                               placeholder="Ulangi password" required>
                    </div>

                    <button type="submit" class="btn-swash">Daftar</button>
                </form>

                <p class="auth-foot">
                    Sudah punya akun?
                    <button type="button" class="link-btn" onclick="flipCard(false)">Masuk</button>
                </p>
            </div>

        </div>
    </div>
</div>

<script>
    var card = document.getElementById('flipCard');
    var front = card.querySelector('.face-front');
    var back = card.querySelector('.face-back');

    function fitHeight() {
        var face = card.classList.contains('flipped') ? back : front;
        card.style.height = face.offsetHeight + 'px';
    }

    function flipCard(toRegister) {
        card.classList.toggle('flipped', toRegister);
        fitHeight();
    }

    fitHeight();
    window.addEventListener('load', fitHeight);
    window.addEventListener('resize', fitHeight);
    if (document.fonts) { document.fonts.ready.then(fitHeight); }
</script>

</body>
</html>