@extends('layouts.app', ['title' => 'Pembayaran & Retribusi Makam'])

@section('content')
<div class="card mb-4" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border: none;">
    <div class="card-body" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <h2 style="margin: 0; font-weight: 700;">Status Retribusi</h2>
                    <div style="position: relative; display: inline-block;">
                        <button id="chatbot-toggle" class="chatbot-btn-mini-light" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; backdrop-filter: blur(5px);">
                            <i data-lucide="bot" style="width: 18px;"></i>
                        </button>
                        <span class="chatbot-tooltip-mini">Asisten AI</span>
                    </div>
                </div>
                <style>
                    .chatbot-btn-mini-light:hover { transform: scale(1.15) rotate(5deg); background: rgba(255,255,255,0.3); }
                    .chatbot-tooltip-mini {
                        visibility: hidden; background-color: var(--gray-900); color: #fff; text-align: center; padding: 4px 10px; border-radius: 6px; position: absolute; z-index: 100; top: 40px; left: 50%; transform: translateX(-50%); opacity: 0; transition: opacity 0.3s, visibility 0.3s; font-size: 0.7rem; white-space: nowrap; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    }
                    .chatbot-tooltip-mini::after { content: ""; position: absolute; bottom: 100%; left: 50%; margin-left: -5px; border-width: 5px; border-style: solid; border-color: transparent transparent var(--gray-900) transparent; }
                    div:hover > .chatbot-tooltip-mini { visibility: visible; opacity: 1; }
                </style>
                <p style="margin-top: 0.5rem; opacity: 0.8;">Kelola pembayaran biaya perawatan dan sewa lahan makam Anda.</p>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 1rem 1.5rem; border-radius: 12px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 0.85rem; opacity: 0.8;">Total Pembayaran Anda</div>
                <div style="font-size: 1.5rem; font-weight: 700;">Rp {{ number_format($payments->where('status', 'confirmed')->sum('amount'), 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Retribusi Makam (Berlaku 8 Bulan)</h3>
    </div>
    <div style="overflow-x: auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Makam</th>
                    <th>Nama Almarhum</th>
                    <th>Tanggal Bayar</th>
                    <th>Masa Akhir (8 Bln)</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Sisa Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                @php
                    $isConfirmed = $payment->status === 'confirmed';
                    $startDate = \Carbon\Carbon::parse($payment->payment_date);
                    $endDate = $startDate->copy()->addMonths(8);
                    $daysLeft = now()->diffInDays($endDate, false);
                    $isExpired = $daysLeft < 0;
                @endphp
                <tr>
                    <td>
                        <strong>{{ $payment->grave->grave_number }}</strong><br>
                        <small>{{ $payment->grave->block_name }}</small>
                    </td>
                    <td>{{ $payment->grave->buried_name ?? 'Belum terisi' }}</td>
                    <td>{{ $startDate->format('d M Y') }}</td>
                    <td>
                        <span style="font-weight: 600; color: {{ $isExpired ? '#ef4444' : '#16a34a' }}">
                            {{ $endDate->format('d M Y') }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge badge-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                    </td>
                    <td>
                        @if($isConfirmed)
                            @if($isExpired)
                                <span class="badge" style="background: #fee2e2; color: #991b1b; padding: 4px 10px;">
                                    <i data-lucide="alert-triangle" style="width: 12px;"></i> Kadaluarsa
                                </span>
                            @else
                                <span class="badge" style="background: #f0fdf4; color: #166534; padding: 4px 10px;">
                                    {{ ceil($daysLeft / 30) }} Bulan lagi
                                </span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--gray-500); padding: 3rem;">
                        <i data-lucide="info" style="width: 48px; height: 48px; opacity: 0.2; margin-bottom: 1rem;"></i><br>
                        Belum ada riwayat pembayaran retribusi makam.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
    <div class="card" style="border-left: 4px solid #f59e0b;">
        <div class="card-body">
            <h4 style="margin-bottom: 0.5rem; color: #92400e;">⚠️ Pemberitahuan Penting</h4>
            <p style="font-size: 0.9rem; color: var(--gray-600);">Sesuai peraturan, retribusi makam berlaku selama <strong>8 bulan</strong>. Customer diwajibkan melakukan perpanjangan sebelum masa akhir berakhir untuk menghindari penghapusan data atau pengalihan lahan.</p>
        </div>
    </div>
    <div class="card" style="border-left: 4px solid var(--primary);">
        <div class="card-body">
            <h4 style="margin-bottom: 0.5rem; color: var(--primary);">💳 Cara Pembayaran</h4>
            <p style="font-size: 0.9rem; color: var(--gray-600);">Untuk pembayaran retribusi selanjutnya atau perpanjangan, silakan hubungi kantor pengelola di area makam atau hubungi admin melalui WhatsApp.</p>
        </div>
    </div>
</div>
@endsection
