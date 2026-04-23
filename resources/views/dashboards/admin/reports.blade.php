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
    
    /* PDF Styling tweaks */
    .pdf-header { display: none; text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div id="report-to-export">
    <div class="print-only pdf-header">
        <h1 style="margin: 0;">LAPORAN KEUANGAN DENAH MAKAM</h1>
        <p>Dicetak pada: {{ date('d M Y H:i') }}</p>
    </div>

    <div class="no-report no-print" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
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
        <div class="no-print" style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; justify-content: flex-end; flex: 1;">
            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap; justify-content: flex-end;">
                <a href="{{ route('admin.reports.excel') }}" class="btn btn-success" style="background: #166534; border: none; display: flex; align-items: center; gap: 4px; height: 36px; padding: 0 0.75rem; font-size: 0.85rem;">
                    <i data-lucide="file-spreadsheet" style="width: 14px;"></i> Excel
                </a>
                <a href="#" class="btn btn-info" style="background: #0891b2; border: none; display: flex; align-items: center; gap: 4px; color: white; height: 36px; padding: 0 0.75rem; font-size: 0.85rem;">
                    <i data-lucide="file-code" style="width: 14px;"></i> CSV
                </a>
                <button onclick="exportToPDF()" class="btn btn-primary" style="display: flex; align-items: center; gap: 4px; background: var(--danger); border: none; height: 36px; padding: 0 0.75rem; font-size: 0.85rem;">
                    <i data-lucide="download" style="width: 14px;"></i> PDF
                </button>
                <button onclick="window.print()" class="btn btn-primary" style="display: flex; align-items: center; gap: 4px; background: var(--gray-800); border: none; height: 36px; padding: 0 0.75rem; font-size: 0.85rem;">
                    <i data-lucide="printer" style="width: 14px;"></i> Print
                </button>
            </div>
            <div style="position: relative; width: 200px;">
                <i data-lucide="search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--gray-400);"></i>
                <input type="text" id="transactionSearch" placeholder="Cari transaksi..." class="form-control" style="padding-left: 30px; width: 100%; font-size: 0.85rem; height: 36px; border: 1px solid var(--gray-200);">
            </div>
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
                        @if($t->grave)
                            {{ $t->grave->grave_number }} ({{ $t->grave->block_name }})
                        @else
                            <span style="color: var(--danger); font-size: 0.8rem;">[Makam Terhapus]</span>
                        @endif
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

    // Client-side Search Logic
    document.getElementById('transactionSearch').addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        let foundAny = false;

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            if (text.includes(query)) {
                row.style.display = '';
                foundAny = true;
            } else {
                row.style.display = 'none';
            }
        });

        // Handle "No results" row if necessary
        const noResults = document.getElementById('noResultsRow');
        if (!foundAny && !noResults) {
            const tbody = document.querySelector('tbody');
            const newRow = document.createElement('tr');
            newRow.id = 'noResultsRow';
            newRow.innerHTML = `<td colspan="6" style="text-align: center; color: var(--gray-500); padding: 2rem;">Data tidak ditemukan.</td>`;
            tbody.appendChild(newRow);
        } else if (foundAny && noResults) {
            noResults.remove();
        }
    });

    // Direct PDF Export Logic
    function exportToPDF() {
        const element = document.getElementById('report-to-export');
        const pdfHeader = document.querySelector('.pdf-header');
        const noPrint = document.querySelectorAll('.no-print');
        const noReport = document.querySelectorAll('.no-report');
        
        // Prepare for export: show PDF header, hide sidebar/buttons/charts
        pdfHeader.style.display = 'block';
        noPrint.forEach(el => el.style.display = 'none');
        noReport.forEach(el => el.style.display = 'none');

        const opt = {
            margin: 10,
            filename: 'Laporan_Keuangan_' + new Date().toISOString().slice(0,10) + '.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true, logging: false },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            // Restore UI
            pdfHeader.style.display = 'none';
            noPrint.forEach(el => el.style.display = '');
            noReport.forEach(el => el.style.display = 'grid'); // Restore as grid
        });
    }
</script>
@endsection
