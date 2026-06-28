<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Writly — Masuk</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/writly-auth.css') }}">
</head>
<body>
<div class="split">

  {{-- Left panel --}}
  <div class="split-left">
    <div class="brand">
      <div class="brand-icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
      </div>
      <span class="brand-name">Writly</span>
    </div>

    <div class="hero">
      <h1 class="hero-title">Tulis lebih<br>cepat, bersama.</h1>
      <p class="hero-desc">Platform catatan kolaboratif real-time untuk tim yang produktif.</p>
    </div>

    <div class="features">
      <div class="feat"><div class="feat-dot"></div><span>Real-time multi-user editing</span></div>
      <div class="feat"><div class="feat-dot"></div><span>Auto-save otomatis</span></div>
      <div class="feat"><div class="feat-dot"></div><span>Version history & restore</span></div>
      <div class="feat"><div class="feat-dot"></div><span>Export PDF & DOCX</span></div>
    </div>
  </div>

  {{-- Right panel --}}
  <div class="split-right">
    <div class="form-card">
      <h2 class="form-title">Selamat datang</h2>
      <p class="form-sub">Masuk untuk melanjutkan</p>

      @if($errors->any())
      <div class="alert-error">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        {{ $errors->first() }}
      </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="field">
          <label class="label" for="email">Email</label>
          <input class="input @error('email') input-err @enderror" type="email" id="email" name="email"
                 value="{{ old('email') }}" placeholder="nama@email.com" required autofocus>
          @error('email')<span class="err-msg">{{ $message }}</span>@enderror
        </div>
        <div class="field">
          <label class="label" for="password">Password</label>
          <input class="input @error('password') input-err @enderror" type="password" id="password"
                 name="password" placeholder="••••••••" required>
          @error('password')<span class="err-msg">{{ $message }}</span>@enderror
        </div>
        <label class="check-row">
          <input type="checkbox" name="remember">
          <span>Ingat saya</span>
        </label>
        <button type="submit" class="btn-submit">Masuk</button>
      </form>

      <p class="switch-link">Belum punya akun? <a href="{{ route('register') }}">Daftar gratis</a></p>
    </div>
  </div>

</div>
</body>
</html>
