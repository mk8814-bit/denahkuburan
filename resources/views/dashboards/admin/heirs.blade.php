@extends('layouts.app', ['title' => 'Database Ahli Waris'])

@section('content')
<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h3 class="card-title">Database Ahli Waris & Penanggung Jawab</h3>
        <form action="{{ route('admin.heirs') }}" method="GET" style="display: flex; gap: 0.5rem; flex-grow: 1; max-width: 400px;">
            <input type="text" name="search" class="form-control" placeholder="Cari nama, almarhum, atau makam..." value="{{ request('search') }}" style="flex-grow: 1; padding: 8px 12px; border: 1px solid var(--gray-300); border-radius: 6px;">
            <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 6px;">
                <i data-lucide="search" style="width: 16px; height: 16px;"></i> Cari
            </button>
        </form>
    </div>
    <div style="overflow-x: auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama Ahli Waris</th>
                    <th>Kontak</th>
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
                        @php
                            $s = strtoupper($grave->status);
                            if ($s === 'OCCUPIED') $label = 'Terisi';
                            elseif ($s === 'AVAILABLE') $label = 'Belum Terisi';
                            elseif ($s === 'BOOKED') $label = 'Dipesan';
                            else $label = $s;
                        @endphp
                        <span class="badge badge-{{ strtolower($grave->status) }}">{{ $label }}</span>
                    </td>
                    <td><small>{{ $grave->notes ?? '-' }}</small></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 2rem; color: var(--gray-500)">Belum ada data ahli waris tersimpan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
