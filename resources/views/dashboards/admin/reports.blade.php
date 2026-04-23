@extends('layouts.app', ['title' => 'Laporan Keuangan'])

@section('content')
<style>
    @media print {
        .sidebar, .navbar, .btn, .no-print, .chart-container, .header { display: none !important; }
        .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .card { box-shadow: none !important; border: none !important; margin: 0 !important; }
        .print-only { display: block !important; }
        body { background: white !important; color: black !important; padding: 0 !important; margin: 0 !important; }
        .table { width: 100% !important; border-collapse: collapse !important; border: 1px solid #000 !important; }
        .table th, .table td { border: 1px solid #000 !important; padding: 8px !important; }
    }
    .print-only { display: none; }
</style>

<div class="print-only">
    <div style="text-align: center; margin-bottom: 2rem; border-bottom: 3px double #000; padding-bottom: 1rem;">
        <h1 style="margin: 0; text-transform: uppercase;">Laporan Keuangan Denah Makam</h1>
        <p style="margin: 5px 0 0 0;">Dicetak pada: {{ date('d M Y H:i') }}</p>
    </div>
</div>

<div class="no-print" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tren Pemasukan Bulanan</h3>
        </div>
        <div class="card-body">
            <div class="chart-container" style="height: 250px;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tren Pemasukan Tahunan</h3>
        </div>
        <div class="card-body">
            <div class="chart-container" style="height: 250px;">
                <canvas id="yearlyChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="card" style="background: linear-gradient(135deg, var(--primary), #2b8a3e); color: white;">
        <div class="card-body" style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; height: 100%;">
            <div style="font-size: 0.9rem; opacity: 0.9; font-weight: 600;">STATUS KEUANGAN SAAT INI</div>
            <div style="font-size: 2.2rem; font-weight: 800; margin: 10px 0;">Rp {{ number_format($total_income, 0, ',', '.') }}</div>
            <div style="font-size: 0.8rem; background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px;">
                Total {{ $transactions->count() }} Transaksi Berhasil
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h3 class="card-title">Detail Transaksi Keuangan</h3>
        <div class="no-print" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="{{ route('admin.reports.excel') }}" class="btn btn-success" style="background: #166534; border: none; display: flex; align-items: center; gap: 6px;">
                <i data-lucide="file-spreadsheet" style="width: 16px;"></i> Excel
            </a>
            <button onclick="window.print()" class="btn btn-primary" style="display: flex; align-items: center; gap: 6px;">
                <i data-lucide="printer" style="width: 16px;"></i> PDF / Cetak
            </button>
        </div>
    </div>
    <div style="overflow-x: auto">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User / Ahli Waris</th>
                    <th>Makam & Blok</th>
                    <th>Tanggal Transaksi</th>
                    <th>Status</th>
                    <th style="text-align: right">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $t)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $t->user->name }}</strong><br>
                        <small style="color: var(--gray-500)">{{ $t->user->email }}</small>
                    </td>
                    <td>
                        {{ $t->grave->grave_number }} ({{ $t->grave->block_name }})
                    </td>
                    <td>{{ \Carbon\Carbon::parse($t->payment_date)->format('d M Y') }}</td>
                    <td><span class="badge badge-success">Selesai</span></td>
                    <td style="text-align: right; font-weight: 700; color: #166534">
                        Rp {{ number_format($t->amount, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--gray-500); padding: 3rem;">Belum ada riwayat transaksi.</td>
                </tr>
                @endforelse
            </tbody>
            @if($transactions->count() > 0)
            <tfoot style="background: var(--gray-50)">
                <tr>
                    <th colspan="5" style="text-align: right">TOTAL KESELURUHAN</th>
                    <th style="text-align: right; font-size: 1.1rem; color: var(--primary)">Rp {{ number_format($total_income, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Monthly Chart
        const mCtx = document.getElementById('monthlyChart').getContext('2d');
        const mData = @json($monthly_reports);
        
        const mLabels = mData.map(item => {
            const date = new Date(item.month + "-01");
            return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
        });
        const mAmounts = mData.map(item => item.total);

        new Chart(mCtx, {
            type: 'line',
            data: {
                labels: mLabels,
                datasets: [{
                    label: 'Pemasukan Bulanan',
                    data: mAmounts,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString() } }
                }
            }
        });

        // Yearly Chart
        const yCtx = document.getElementById('yearlyChart').getContext('2d');
        const yData = @json($yearly_reports);
        
        const yLabels = yData.map(item => item.year);
        const yAmounts = yData.map(item => item.total);

        new Chart(yCtx, {
            type: 'bar',
            data: {
                labels: yLabels,
                datasets: [{
                    label: 'Pemasukan Tahunan',
                    data: yAmounts,
                    backgroundColor: '#10b981',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString() } }
                }
            }
        });
    });
</script>
@endsection
