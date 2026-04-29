@extends('layouts.app', ['title' => 'Cek & Tambah Makam'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Makam Baru</h3>
    </div>
    <form action="{{ route('karyawan.graves.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem">
            <div class="form-group">
                <label class="form-label">Nama Blok</label>
                <input type="text" name="block_name" class="form-control" placeholder="Blok B" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Makam</label>
                <input type="text" name="grave_number" class="form-control" placeholder="B01" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Almarhum (Opsional)</label>
                <input type="text" name="buried_name" class="form-control" placeholder="Nama Almarhum">
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Dimakamkan (Opsional)</label>
                <input type="date" name="burial_date" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Nama Ahli Waris (Opsional)</label>
                <input type="text" name="heir_name" class="form-control" placeholder="Nama Ahli Waris">
            </div>
            <div class="form-group">
                <label class="form-label">Kontak Ahli Waris (Opsional)</label>
                <input type="text" name="heir_contact" class="form-control" placeholder="08123456789">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Data Makam</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Makam</h3>
        <form action="{{ route('karyawan.graves') }}" method="GET" style="display: flex; gap: 0.5rem">
            <input type="text" name="search" class="form-control" placeholder="Cari nama/nomor..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-secondary">Cari</button>
        </form>
    </div>
    <div style="overflow-x: auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Blok</th>
                    <th>No.</th>
                    <th>Almarhum</th>
                    <th>Ahli Waris</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($graves as $grave)
                <tr>
                    <td>{{ $grave->block_name }}</td>
                    <td>{{ $grave->grave_number }}</td>
                    <td>{{ $grave->buried_name ?? '-' }}</td>
                    <td>{{ $grave->heir_name ?? '-' }}</td>
                    <td>
                        @php
                            $s = strtolower($grave->status);
                            if ($s === 'occupied') $label = 'Terisi';
                            elseif ($s === 'available') $label = 'Belum Terisi';
                            elseif ($s === 'booked') $label = 'Dipesan';
                            else $label = $grave->status;
                        @endphp
                        <span class="badge badge-{{ $s }}">{{ $label }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
