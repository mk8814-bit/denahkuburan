@extends('layouts.app', ['title' => 'Manajemen Reservasi'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Reservasi / Pesanan Makam</h3>
    </div>
    <div style="overflow-x: auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Blok</th>
                    <th>No. Makam</th>
                    <th>Pemesan (Ahli Waris)</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $grave)
                <tr>
                    <td>{{ $grave->block_name }}</td>
                    <td>{{ $grave->grave_number }}</td>
                    <td>{{ $grave->heir_name ?? '-' }}</td>
                    <td>{{ $grave->heir_contact ?? '-' }}</td>
                    <td><span class="badge badge-booked">Dipesan</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <form action="{{ route('admin.reservations.update', $grave->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="occupied">
                                <button type="submit" class="btn btn-primary" style="font-size: 0.75rem; padding: 4px 10px;" onclick="return confirm('Tandai makam ini sebagai Sudah Terisi (Meninggal)?')">
                                    <i data-lucide="check" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i> Jadi Terisi
                                </button>
                            </form>
                            <form action="{{ route('admin.reservations.update', $grave->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="available">
                                <button type="submit" class="btn btn-danger" style="font-size: 0.75rem; padding: 4px 10px;" onclick="return confirm('Apakah Anda yakin ingin Dibatalkan? Makam akan kembali berstatus Tersedia.')">
                                    <i data-lucide="x" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i> Batalkan
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: var(--gray-500)">Belum ada data reservasi aktif.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
