@extends('layouts.app', ['title' => 'Pengaturan Global'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Konfigurasi Sistem</h3>
    </div>
    <form action="{{ route('super-admin.settings.update') }}" method="POST">
        @csrf
        @foreach($settings as $setting)
        <div class="form-group">
            <label class="form-label">{{ $setting->description ?? $setting->key }}</label>
            <input type="text" name="settings[{{ $setting->key }}]" class="form-control" value="{{ $setting->value }}">
        </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection
