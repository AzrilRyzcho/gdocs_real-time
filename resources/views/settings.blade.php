<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Pengaturan — Writly</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Google+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'Google Sans','Inter',sans-serif;background:#f8f9fa;color:#202124;min-height:100vh;}
.topbar{height:64px;display:flex;align-items:center;padding:0 24px;background:#fff;border-bottom:1px solid #e8eaed;}
.topbar a{text-decoration:none;display:flex;align-items:center;gap:8px;color:#5f6368;font-size:14px;font-weight:500;}
.topbar a:hover{color:#202124;}
.topbar h1{font-size:18px;font-weight:500;color:#202124;margin-left:16px;}
.container{max-width:640px;margin:40px auto;padding:0 24px;}
.card{background:#fff;border-radius:8px;border:1px solid #e8eaed;padding:28px;margin-bottom:20px;}
.card h2{font-size:16px;font-weight:500;color:#202124;margin-bottom:4px;}
.card p{font-size:13px;color:#5f6368;margin-bottom:20px;}
.field{margin-bottom:16px;}
.field label{display:block;font-size:13px;font-weight:500;color:#5f6368;margin-bottom:6px;}
.field input{width:100%;height:42px;padding:0 14px;border:1px solid #dadce0;border-radius:4px;font-size:14px;color:#202124;font-family:inherit;outline:none;transition:border-color .15s;}
.field input:focus{border-color:#1a73e8;box-shadow:0 0 0 2px rgba(26,115,232,.15);}
.btn{height:38px;padding:0 24px;background:#1a73e8;color:#fff;border:none;border-radius:4px;font-size:14px;font-weight:500;cursor:pointer;font-family:inherit;transition:background .15s;}
.btn:hover{background:#1557b0;}
.success{background:#e6f4ea;color:#137333;padding:10px 14px;border-radius:4px;font-size:13px;margin-bottom:16px;border:1px solid #ceead6;}
.error-msg{color:#d93025;font-size:12px;margin-top:4px;}
.user-info{display:flex;align-items:center;gap:16px;margin-bottom:20px;}
.user-av{width:56px;height:56px;border-radius:50%;background:#1a73e8;color:#fff;font-size:22px;font-weight:500;display:flex;align-items:center;justify-content:center;}
.user-detail h3{font-size:16px;color:#202124;font-weight:500;}
.user-detail p{font-size:13px;color:#5f6368;margin-top:2px;}
</style>
</head>
<body>
<div class="topbar">
  <a href="{{ route('documents.index') }}">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Kembali
  </a>
  <h1>Pengaturan</h1>
</div>

<div class="container">
  @if(session('success'))
  <div class="success">✓ {{ session('success') }}</div>
  @endif

  {{-- Profil --}}
  <div class="card">
    <div class="user-info">
      <div class="user-av">{{ strtoupper(mb_substr(auth()->user()->name,0,1)) }}</div>
      <div class="user-detail">
        <h3>{{ auth()->user()->name }}</h3>
        <p>{{ auth()->user()->email }}</p>
      </div>
    </div>
    <h2>Edit Profil</h2>
    <p>Ubah nama tampilan Anda</p>
    <form method="POST" action="{{ route('settings.profile') }}">
      @csrf
      <div class="field">
        <label for="name">Nama</label>
        <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>
        @error('name')<div class="error-msg">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="btn">Simpan Profil</button>
    </form>
  </div>

  {{-- Ganti Password --}}
  <div class="card">
    <h2>Ganti Password</h2>
    <p>Pastikan menggunakan password yang kuat dan unik</p>
    <form method="POST" action="{{ route('settings.password') }}">
      @csrf
      <div class="field">
        <label for="current_password">Password Saat Ini</label>
        <input type="password" id="current_password" name="current_password" required>
        @error('current_password')<div class="error-msg">{{ $message }}</div>@enderror
      </div>
      <div class="field">
        <label for="password">Password Baru</label>
        <input type="password" id="password" name="password" required>
        @error('password')<div class="error-msg">{{ $message }}</div>@enderror
      </div>
      <div class="field">
        <label for="password_confirmation">Konfirmasi Password Baru</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
      </div>
      <button type="submit" class="btn">Ubah Password</button>
    </form>
  </div>
</div>
</body>
</html>
