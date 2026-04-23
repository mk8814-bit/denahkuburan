@extends('layouts.app', ['title' => 'Login'])

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 style="color: var(--primary); font-size: 2rem; letter-spacing: -0.025em">DenahMakam</h1>
            <p style="color: var(--secondary)">Silakan masuk ke akun Anda</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@example.com" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem">Masuk</button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem">
            <p style="font-size: 0.875rem; color: var(--gray-600)">Belum punya akun? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600">Daftar</a></p>
        </div>
    </div>
</div>
@endsection
