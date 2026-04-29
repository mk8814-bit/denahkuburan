@extends('layouts.app', ['title' => 'Login'])

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 style="color: var(--primary); font-size: 2rem; letter-spacing: -0.025em">{{ \App\Models\Setting::where('key', 'cemetery_name')->value('value') ?? 'DenahMakam' }}</h1>
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

        <div style="margin: 1.5rem 0; display: flex; align-items: center; text-align: center;">
            <div style="flex: 1; border-top: 1px solid var(--gray-200);"></div>
            <span style="padding: 0 1rem; color: var(--gray-500); font-size: 0.875rem;">Atau</span>
            <div style="flex: 1; border-top: 1px solid var(--gray-200);"></div>
        </div>

        <a href="{{ route('google.login') }}" class="btn btn-outline" style="width: 100%; padding: 0.875rem; margin-bottom: 1rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background-color: white; border: 1px solid var(--gray-300); border-radius: 0.5rem; color: var(--gray-700); font-weight: 500; cursor: pointer; transition: background-color 0.2s; text-decoration: none;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
            </svg>
            Login dengan Google
        </a>

        <button type="button" onclick="window.location.href='{{ url('/') }}'" class="btn btn-outline" style="width: 100%; padding: 0.875rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background-color: white; border: 1px solid var(--gray-300); border-radius: 0.5rem; color: var(--gray-700); font-weight: 500; cursor: pointer; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f8f9fa'" onmouseout="this.style.backgroundColor='white'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
            Login dengan OTP
        </button>

        <div style="text-align: center; margin-top: 1.5rem">
            <p style="font-size: 0.875rem; color: var(--gray-600)">Belum punya akun? <a href="{{ route('register') }}" style="color: var(--primary); font-weight: 600">Daftar</a></p>
        </div>
    </div>
</div>
@endsection
