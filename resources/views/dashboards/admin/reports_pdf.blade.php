<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Denah Makam</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 12px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            font-size: 11px;
            border-radius: 12px;
            background-color: #28a745;
            color: white;
        }
        .total-row th {
            background-color: #e9ecef;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Keuangan Denah Makam</h1>
        <p>Dicetak pada: {{ date('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">User / Ahli Waris</th>
                <th width="25%">Makam & Blok</th>
                <th width="15%">Tanggal</th>
                <th width="10%">Status</th>
                <th width="20%" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $t)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $t->user->name ?? '-' }}</strong><br>
                    <small style="color: #666;">{{ $t->user->email ?? '' }}</small>
                </td>
                <td>
                    {{ $t->grave->grave_number ?? '-' }} ({{ $t->grave->block_name ?? '-' }})
                </td>
                <td>{{ \Carbon\Carbon::parse($t->payment_date)->format('d M Y') }}</td>
                <td class="text-center"><span class="badge">Selesai</span></td>
                <td class="text-right">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Belum ada riwayat transaksi.</td>
            </tr>
            @endforelse
        </tbody>
        @if($transactions->count() > 0)
        <tfoot>
            <tr class="total-row">
                <th colspan="5" class="text-right">TOTAL KESELURUHAN</th>
                <th class="text-right">Rp {{ number_format($total_income, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>
