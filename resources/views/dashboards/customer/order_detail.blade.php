@extends('layouts.app', ['title' => 'Detail Pemesanan'])

@section('content')
<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header" style="background: var(--primary); color: white;">
        <h3 class="card-title">Konfirmasi & Detail Pemesanan Makam</h3>
    </div>
    <div class="card-body" style="padding: 2.5rem;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: start;">
            <!-- Order Details -->
            <div>
                <h4 style="border-bottom: 2px solid var(--gray-100); padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="file-text"></i> Informasi Pesanan
                </h4>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="color: var(--gray-500); font-size: 0.85rem;">Lokasi Makam</label>
                        <div style="font-weight: 700; font-size: 1.1rem;">{{ $grave->block_name }} - {{ $grave->grave_number }}</div>
                    </div>
                    <div>
                        <label style="color: var(--gray-500); font-size: 0.85rem;">Agama</label>
                        <div style="font-weight: 600;">{{ ucfirst($grave->religion) }}</div>
                    </div>
                    <div>
                        <label style="color: var(--gray-500); font-size: 0.85rem;">Nama Calon Almarhum</label>
                        <div style="font-weight: 600;">{{ $grave->buried_name ?? '-' }}</div>
                    </div>
                    <div>
                        <label style="color: var(--gray-500); font-size: 0.85rem;">Email Pemesan</label>
                        <div style="font-weight: 600;">{{ auth()->user()->email }}</div>
                    </div>
                    <div style="background: #f0fdf4; padding: 1rem; border-radius: 8px; border: 1px dashed #16a34a; margin-top: 1rem;">
                        <label style="color: #166534; font-size: 0.85rem; font-weight: 600;">Total Biaya (Midtrans VA)</label>
                        <div style="font-size: 1.5rem; font-weight: 800; color: #166534;">Rp 2.500.000</div>
                    </div>
                </div>
            </div>

            <!-- Payment Simulation -->
            <div id="paymentSection">
                <h4 style="border-bottom: 2px solid var(--gray-100); padding-bottom: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="credit-card"></i> Pembayaran (Virtual Account)
                </h4>
                
                <div id="vaStep">
                    <p style="font-size: 0.9rem; color: var(--gray-600); line-height: 1.6;">Silakan klik tombol di bawah untuk membayar menggunakan Midtrans Virtual Account Bank (BCA/Mandiri/BNI).</p>
                    
                    <button onclick="simulateMidtrans()" class="btn btn-primary" style="width: 100%; height: 60px; font-size: 1.1rem; margin-top: 1.5rem; display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <i data-lucide="shield-check"></i> Bayar Sekarang via Midtrans
                    </button>
                    
                    <div style="margin-top: 2rem; display: flex; justify-content: center; gap: 1.5rem; opacity: 0.5;">
                        <img src="{{ asset('bca.svg') }}" style="height: 20px;" alt="BCA">
                        <img src="{{ asset('mandiri.svg') }}" style="height: 20px;" alt="Mandiri">
                        <img src="{{ asset('bni.png') }}" style="height: 20px;" alt="BNI">
                    </div>
                </div>

                <!-- Proof Upload (Hidden initially) -->
                <div id="proofUploadStep" style="display: none; animation: fadeIn 0.5s;">
                    <div class="badge" style="background: #dcfce7; color: #166534; margin-bottom: 1rem; width: 100%; text-align: center; padding: 10px;">
                        <i data-lucide="check-circle" style="width: 16px;"></i> Pembayaran VA Berhasil!
                    </div>
                    <label class="form-label">Unggah Bukti Transaksi Midtrans / Bank</label>
                    <p style="font-size: 0.8rem; color: var(--gray-500); margin-bottom: 1rem;">Mohon lampirkan screenshot atau foto bukti bayar untuk verifikasi akhir oleh admin.</p>
                    
                    <form action="{{ route('customer.order.upload_proof', $grave->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="border: 2px dashed var(--gray-200); padding: 2rem; border-radius: 12px; text-align: center;">
                             <input type="file" name="proof" id="proofInput" style="display: none;" accept="image/*" required onchange="updateFileName()">
                             <label for="proofInput" style="cursor: pointer;">
                                 <i data-lucide="upload-cloud" style="width: 48px; height: 48px; color: var(--primary); margin-bottom: 10px;"></i>
                                 <div id="fileName" style="font-weight: 600; color: var(--gray-700);">Pilih file foto bukti</div>
                             </label>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem; padding: 1rem;">Kirim Bukti Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    function simulateMidtrans() {
        if(confirm('Simulasi Midtrans: Pengalihan ke gerbang pembayaran kustom Midtrans VA. Klik OK untuk mensimulasikan pembayaran Berhasil.')) {
            document.getElementById('vaStep').style.display = 'none';
            document.getElementById('proofUploadStep').style.display = 'block';
            lucide.createIcons();
        }
    }

    function updateFileName() {
        const input = document.getElementById('proofInput');
        const nameDisplay = document.getElementById('fileName');
        if (input.files.length > 0) {
            nameDisplay.innerText = input.files[0].name;
            nameDisplay.style.color = '#16a34a';
        }
    }
</script>
@endsection
