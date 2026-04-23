@extends('layouts.app', ['title' => 'Register'])

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 style="color: var(--primary); font-size: 2rem; letter-spacing: -0.025em">Daftar Akun</h1>
            <p style="color: var(--secondary)">Silakan buat akun untuk mengakses sistem</p>
        </div>

        @if($errors->any())
            <div class="card" style="background: #fef2f2; border-left: 4px solid var(--danger); color: #991b1b; padding: 0.75rem; margin-bottom: 1rem; font-size: 0.875rem">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" placeholder="John Doe" required value="{{ old('name') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="yourname@gmail.com" required value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.875rem">Daftar Sekarang</button>
        </form>

        <div style="text-align: center; margin-top: 1.5rem">
            <p style="font-size: 0.875rem; color: var(--gray-600)">Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600">Masuk</a></p>
        </div>
    </div>
</div>
@endsection
