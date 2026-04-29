<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Grave;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('dashboards.admin.index', [
            'total_graves' => Grave::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ]);
    }

    public function graves()
    {
        return view('dashboards.admin.graves', [
            'graves' => Grave::latest()->get(),
        ]);
    }

    public function payments()
    {
        return view('dashboards.admin.payments', [
            'payments' => Payment::with(['user', 'grave'])->latest()->get(),
        ]);
    }

    public function reports()
    {
        // Monthly report data
        $monthly_reports = Payment::where('status', 'confirmed')
            ->selectRaw('DATE_FORMAT(payment_date, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Yearly report data
        $yearly_reports = Payment::where('status', 'confirmed')
            ->selectRaw('DATE_FORMAT(payment_date, "%Y") as year, SUM(amount) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        $transactions = Payment::with(['user', 'grave'])
            ->where('status', 'confirmed')
            ->latest('payment_date')
            ->get();

        $total_income = $transactions->sum('amount');

        return view('dashboards.admin.reports', [
            'monthly_reports' => $monthly_reports,
            'yearly_reports' => $yearly_reports,
            'transactions' => $transactions,
            'total_income' => $total_income,
        ]);
    }

    public function exportExcel()
    {
        $transactions = Payment::with(['user', 'grave'])
            ->where('status', 'confirmed')
            ->latest('payment_date')
            ->get();

        $filename = "Laporan_Keuangan_Excel_" . date('Y-m-d') . ".csv"; // Using CSV for generic Excel compatibility
        $this->generateFileStream($transactions, $filename);
    }

    public function exportPdf()
    {
        $transactions = Payment::with(['user', 'grave'])
            ->where('status', 'confirmed')
            ->latest('payment_date')
            ->get();

        $total_income = $transactions->sum('amount');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboards.admin.reports_pdf', [
            'transactions' => $transactions,
            'total_income' => $total_income
        ]);

        return $pdf->download("Laporan_Keuangan_" . date('Y-m-d') . ".pdf");
    }

    private function generateFileStream($transactions, $filename)
    {
        $handle = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        fputcsv($handle, ['LAPORAN KEUANGAN DENAH MAKAM']);
        fputcsv($handle, ['Dicetak pada: ' . date('d M Y H:i')]);
        fputcsv($handle, []);
        fputcsv($handle, ['No', 'User', 'Makam', 'Tanggal', 'Catatan', 'Jumlah']);

        foreach ($transactions as $index => $t) {
            fputcsv($handle, [
                $index + 1,
                $t->user->name ?? 'User Hilang',
                ($t->grave->grave_number ?? '-') . ' (' . ($t->grave->block_name ?? '-') . ')',
                $t->payment_date,
                $t->notes,
                $t->amount
            ]);
        }

        fputcsv($handle, []);
        fputcsv($handle, ['', '', '', '', 'TOTAL PEMASUKAN', $transactions->sum('amount')]);

        fclose($handle);
        exit;
    }

    public function storeGrave(Request $request)
    {
        $validated = $request->validate([
            'block_name' => 'required',
            'grave_number' => 'required',
            'buried_name' => 'nullable',
            'burial_date' => 'nullable|date',
            'heir_name' => 'nullable',
            'heir_contact' => 'nullable',
            'status' => 'required|in:available,occupied,booked',
            'religion' => 'nullable|string',
        ]);

        Grave::create($validated);

        return back()->with('success', 'Data makam berhasil ditambahkan.');
    }

    public function generateAutoBlock(Request $request)
    {
        $validated = $request->validate([
            'religion' => 'required|string',
            'amount' => 'required|integer|min:1|max:100',
        ]);

        $religion = $validated['religion'];
        $amount = $validated['amount'];

        // Cek apakah masih ada makam kosong untuk agama tersebut
        $availableCount = Grave::where('religion', $religion)->where('status', 'available')->count();
        if ($availableCount > 0) {
            return back()->with('error', "Gagal membuat blok otomatis. Masih ada $availableCount makam kosong (belum terisi) untuk agama " . ucfirst($religion) . " di blok sebelumnya.");
        }

        // Cari blok yang sudah ada untuk agama ini
        $existingBlocks = Grave::where('religion', $religion)->pluck('block_name')->unique()->toArray();
        
        // Cari blok yang berakhiran .1 (contoh: A.1, B.1, dst)
        $suffixBlocks = array_filter($existingBlocks, function($name) {
            return preg_match('/^[A-Z]\.1$/', strtoupper($name));
        });

        if (empty($suffixBlocks)) {
            $nextBlockName = 'A.1';
        } else {
            // Ambil huruf pertama dari blok-blok tersebut
            $letters = array_map(function($name) {
                return substr(strtoupper($name), 0, 1);
            }, $suffixBlocks);
            
            $maxLetter = max($letters);
            
            // Jika sudah mencapai Z, tidak otomatis lanjut ke AA (bisa disesuaikan nanti jika butuh)
            if ($maxLetter == 'Z') {
                return back()->with('error', "Batas pembuatan blok otomatis (Z.1) telah tercapai.");
            }
            
            $nextLetter = ++$maxLetter;
            $nextBlockName = $nextLetter . '.1';
        }

        // Generate makam
        for ($i = 1; $i <= $amount; $i++) {
            Grave::create([
                'block_name' => $nextBlockName,
                'grave_number' => str_pad($i, 2, '0', STR_PAD_LEFT), // 01, 02, dst.
                'religion' => $religion,
                'status' => 'available',
            ]);
        }

        return back()->with('success', "Berhasil menambahkan $amount makam baru secara otomatis di blok $nextBlockName untuk agama " . ucfirst($religion) . ".");
    }

    public function updateGrave(Request $request, Grave $grave)
    {
        $validated = $request->validate([
            'block_name' => 'required',
            'grave_number' => 'required',
            'buried_name' => 'nullable',
            'burial_date' => 'nullable|date',
            'heir_name' => 'nullable',
            'status' => 'required|in:available,occupied,booked',
            'religion' => 'nullable|string',
        ]);

        $grave->update($validated);

        return back()->with('success', 'Data makam berhasil diperbarui.');
    }

    public function deleteGrave(Grave $grave)
    {
        $grave->delete();
        return back()->with('success', 'Data makam berhasil dihapus.');
    }

    public function updatePayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,failed',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return back()->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function confirmPayment(Payment $payment)
    {
        $payment->update([
            'status' => 'confirmed',
            'payment_date' => $payment->payment_date ?? now(),
        ]);

        // Update grave status to occupied
        if ($payment->grave) {
            $payment->grave->update(['status' => 'occupied']);
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function deletePayment(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'Data pembayaran berhasil dihapus.');
    }

    public function reservations()
    {
        return view('dashboards.admin.reservations', [
            'reservations' => Grave::where('status', 'booked')->latest()->get(),
        ]);
    }

    public function updateReservation(Request $request, Grave $grave)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,occupied,booked',
        ]);

        $grave->update($validated);
        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function maintenance()
    {
        return view('dashboards.admin.maintenance', [
            'maintenances' => \App\Models\Maintenance::latest()->get(),
            'graves' => Grave::all(),
        ]);
    }

    public function storeMaintenance(Request $request)
    {
        $validated = $request->validate([
            'block_name' => 'required',
            'description' => 'required',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:dijadwalkan,sedang_dikerjakan,selesai',
            'notes' => 'nullable',
        ]);

        \App\Models\Maintenance::create($validated);
        return back()->with('success', 'Jadwal pemeliharaan berhasil ditambahkan.');
    }

    public function updateMaintenance(Request $request, \App\Models\Maintenance $maintenance)
    {
        $validated = $request->validate([
            'block_name' => 'required',
            'description' => 'required',
            'scheduled_date' => 'required|date',
            'status' => 'required|in:dijadwalkan,sedang_dikerjakan,selesai',
            'notes' => 'nullable',
        ]);

        $maintenance->update($validated);
        return back()->with('success', 'Jadwal pemeliharaan berhasil diperbarui.');
    }

    public function completeMaintenance(\App\Models\Maintenance $maintenance)
    {
        $maintenance->update(['status' => 'selesai']);
        return back()->with('success', 'Status pemeliharaan berhasil ditandai sebagai selesai.');
    }

    public function progressMaintenance(\App\Models\Maintenance $maintenance)
    {
        $maintenance->update(['status' => 'sedang_dikerjakan']);
        return back()->with('success', 'Status pemeliharaan berhasil ditandai sebagai sedang dikerjakan.');
    }

    public function deleteMaintenance(\App\Models\Maintenance $maintenance)
    {
        $maintenance->delete();
        return back()->with('success', 'Jadwal pemeliharaan berhasil dihapus.');
    }

    public function heirs(Request $request)
    {
        $query = Grave::whereNotNull('heir_name');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('heir_name', 'like', "%{$search}%")
                  ->orWhere('buried_name', 'like', "%{$search}%")
                  ->orWhere('block_name', 'like', "%{$search}%")
                  ->orWhere('grave_number', 'like', "%{$search}%");
            });
        }

        return view('dashboards.admin.heirs', [
            'heirs' => $query->latest()->get(),
        ]);
    }
}
