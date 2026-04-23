@extends('layouts.app', ['title' => 'Database Ahli Waris'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Database Ahli Waris & Penanggung Jawab</h3>
    </div>
    <div style="overflow-x: auto">
        <table class="table">
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
@endsection
