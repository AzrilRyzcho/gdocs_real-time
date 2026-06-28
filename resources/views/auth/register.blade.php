<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Writly — Daftar</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/writly-auth.css') }}">
</head>
<body>
<div class="split">

  <div class="split-left">
    <div class="brand">
      <div class="brand-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
      </div>
      <span class="brand-name">Writly</span>
    </div>
    <div class="hero">
      <h1 class="hero-title">Mulai menulis<br>hari ini.</h1>
      <p class="hero-desc">Bergabung dan rasakan produktivitas kolaboratif yang sesungguhnya.</p>
    </div>
    <div class="features">
      <div class="feat"><div class="feat-dot"></div><span>Gratis tanpa batas catatan</span></div>
      <div class="feat"><div class="feat-dot"></div><span>Kolaborasi real-time</span></div>
      <div class="feat"><div class="feat-dot"></div><span>Akses dari mana saja di LAN</span></div>
    </div>
  </div>

  <div class="split-right">
    <div class="form-card">
      <h2 class="form-title">Buat akun baru</h2>
      <p class="form-sub">Daftar dan mulai produktif sekarang</p>

      @if($errors->any())
      <div class="alert-error">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ $errors->first() }}
      </div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="field">
          <label class="label" for="name">Nama lengkap</label>
          <input class="input @error('name') input-err @enderror" type="text" id="name" name="name"
                 value="{{ old('name') }}" placeholder="Nama kamu" required autofocus>
          @error('name')<span class="err-msg">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label class="label" for="email">Email</label>
          <input class="input @error('email') input-err @enderror" type="email" id="email" name="email"
                 value="{{ old('email') }}" placeholder="nama@email.com" required>
          @error('email')<span class="err-msg">{{ $message }}</span>@enderror
        </div>
        <div class="field-row">
          <div class="field">
            <label class="label" for="password">Password</label>
            <input class="input @error('password') input-err @enderror" type="password" id="password"
                   name="password" placeholder="Min. 6 karakter" required>
            @error('password')<span class="err-msg">{{ $message }}</span>@enderror
          </div>
          <div class="field">
            <label class="label" for="password_confirmation">Konfirmasi</label>
            <input class="input" type="password" id="password_confirmation"
                   name="password_confirmation" placeholder="Ulangi password" required>
          </div>
        </div>
        <button type="submit" class="btn-submit">Buat akun</button>
      </form>

      <p class="switch-link">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
    </div>
  </div>
</div>
</body>
</html>
