@extends('layouts.app')

@section('content')
<style>
    .login-container {
        max-width: 450px;
        margin: 4rem auto;
    }
    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .login-header h2 {
        font-family: var(--font-display);
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 0.5rem;
    }
    .login-header h2 span {
        background: linear-gradient(135deg, var(--primary-accent), var(--primary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .login-header p {
        color: var(--text-muted);
        font-size: 0.95rem;
    }
    .demo-credentials {
        background: rgba(255, 255, 255, 0.02);
        border: 1px dashed var(--border-glass);
        padding: 1rem;
        border-radius: var(--radius);
        margin-top: 1.5rem;
        font-size: 0.8rem;
    }
    .demo-credentials h4 {
        margin-bottom: 0.5rem;
        color: var(--text-main);
        font-weight: 600;
    }
    .demo-credentials code {
        background: rgba(0, 0, 0, 0.3);
        padding: 0.1rem 0.3rem;
        border-radius: 4px;
        color: var(--primary-accent);
    }
</style>

<div class="login-container">
    <div class="login-header">
        <h2>PT Maju Jaya <span>Penggajian</span></h2>
        <p>Silakan masuk untuk mengakses dashboard Anda</p>
    </div>

    <div class="card">
        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="contoh@majujaya.com" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password Anda" required>
            </div>

            <button type="submit" class="btn" style="width: 100%; margin-top: 1rem;">
                Masuk ke Akun
            </button>
        </form>
    </div>
</div>
@endsection
