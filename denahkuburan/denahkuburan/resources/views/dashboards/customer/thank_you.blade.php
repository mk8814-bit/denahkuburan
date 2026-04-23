@extends('layouts.app', ['title' => 'Terima Kasih'])

@section('content')
<div class="card" style="max-width: 600px; margin: 4rem auto; text-align: center; padding: 3rem; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.1);">
    <div style="width: 80px; height: 80px; background: #f0fdf4; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
        <i data-lucide="heart" style="width: 40px; height: 40px; fill: #16a34a;"></i>
    </div>
    
    <h2 style="font-weight: 800; color: var(--gray-900); margin-bottom: 1rem;">Terima Kasih Atas Kepercayaan Anda</h2>
    
    <div style="font-size: 1.1rem; color: var(--gray-600); line-height: 1.8; margin-bottom: 2rem;">
        Bukti pembayaran Anda telah kami terima dan sedang diproses oleh tim admin kami. Kami akan melakukan verifikasi secepat mungkin.
    </div>

    <div style="background: #f8fafc; padding: 2rem; border-radius: 16px; border-left: 5px solid var(--primary); margin-bottom: 2rem; text-align: left;">
        <h4 style="margin: 0; font-family: 'Inter', sans-serif; font-style: italic; color: var(--gray-700);">
            "Segenap tim manajemen pemakaman turut berbela sungkawa yang sedalam-dalamnya atas kepergian almarhum/almarhumah. Semoga keluarga yang ditinggalkan diberikan ketabahan dan kekuatan."
        </h4>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.8rem;">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-primary" style="padding: 1rem;">Kembali ke Beranda</a>
        <a href="{{ route('customer.payments') }}" class="btn btn-secondary" style="padding: 1rem;">Lihat Status Pembayaran</a>
    </div>
    
    <p style="margin-top: 2rem; color: var(--gray-400); font-size: 0.85rem;">Jika ada pertanyaan, silakan hubungi tim bantuan kami melalui menu bantuan.</p>
</div>
@endsection
