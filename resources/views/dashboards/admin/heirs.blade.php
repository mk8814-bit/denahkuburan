@extends('layouts.app', ['title' => 'Database Ahli Waris'])

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h3 class="card-title">Database Ahli Waris & Penanggung Jawab</h3>
        <div style="position: relative;">
            <i data-lucide="search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--gray-400);"></i>
            <input type="text" id="heirSearch" placeholder="Cari ahli waris..." class="form-control" style="padding-left: 30px; width: 220px; font-size: 0.85rem; height: 38px;">
        </div>
    </div>
    <div style="overflow-x: auto">
        <table class="table" id="heirsTable">
            <thead>
                <tr>
                    <th>Nama Ahli Waris</th>
                    <th>Kontak</th>
                    <th>Almarhum / Lokasi</th>
                    <th>Status Makam</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($heirs as $grave)
                <tr>
                    <td><strong>{{ $grave->heir_name }}</strong></td>
                    <td>{{ $grave->heir_contact ?? '-' }}</td>
                    <td>
                        {{ $grave->buried_name ?? 'Pesanan Kavling' }}<br>
                        <small class="text-muted">{{ $grave->block_name }} / {{ $grave->grave_number }}</small>
                    </td>
                    <td><span class="badge badge-{{ $grave->status }}">{{ $grave->status }}</span></td>
                    <td><small>{{ $grave->notes ?? '-' }}</small></td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem; color: var(--gray-500)">Belum ada data ahli waris tersimpan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('heirSearch').addEventListener('keyup', function() {
            const query = this.value.toLowerCase();
            const rows = document.querySelectorAll('#heirsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    });
</script>
@endsection
