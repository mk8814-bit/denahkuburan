@extends('layouts.app', ['title' => 'Data Makam'])

@section('content')
<div class="card" id="addGraveForm" style="display: none; margin-bottom: 2rem">
    <div class="card-header">
        <h3 class="card-title">Tambah Makam Baru</h3>
    </div>
    <form action="{{ route('admin.graves.store') }}" method="POST">
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
                <label class="form-label">Agama</label>
                <select name="religion" class="form-control" required>
                    <option value="islam">Islam</option>
                    <option value="protestan">Protestan</option>
                    <option value="katolik">Katolik</option>
                    <option value="hindu">Hindu</option>
                    <option value="budha">Budha</option>
                    <option value="konghucu">Konghucu</option>
                    <option value="umum">Umum</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Almarhum</label>
                <input type="text" name="buried_name" class="form-control" placeholder="Nama Almarhum">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="available">Tersedia</option>
                    <option value="occupied">Terisi</option>
                    <option value="booked">Dipesan</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan data</button>
        <button type="button" class="btn btn-secondary" onclick="toggleGraveForm()">Batal</button>
    </form>
</div>

<div class="card" id="editGraveForm" style="display: none; margin-bottom: 2rem">
    <div class="card-header">
        <h3 class="card-title">Edit Data Makam</h3>
    </div>
    <form id="editGraveAction" method="POST">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem">
            <div class="form-group">
                <label class="form-label">Nama Blok</label>
                <input type="text" name="block_name" id="edit_block_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Makam</label>
                <input type="text" name="grave_number" id="edit_grave_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Agama</label>
                <select name="religion" id="edit_religion" class="form-control" required>
                    <option value="islam">Islam</option>
                    <option value="protestan">Protestan</option>
                    <option value="katolik">Katolik</option>
                    <option value="hindu">Hindu</option>
                    <option value="budha">Budha</option>
                    <option value="konghucu">Konghucu</option>
                    <option value="umum">Umum</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Almarhum</label>
                <input type="text" name="buried_name" id="edit_buried_name" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Ahli Waris</label>
                <input type="text" name="heir_name" id="edit_heir_name" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" id="edit_status" class="form-control" required>
                    <option value="available">Tersedia</option>
                    <option value="occupied">Terisi</option>
                    <option value="booked">Dipesan</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Data</button>
        <button type="button" class="btn btn-secondary" onclick="toggleEditGraveForm()">Batal</button>
    </form>
</div>

<div class="card" id="autoGenerateForm" style="display: none; margin-bottom: 2rem; border-left: 4px solid var(--success);">
    <div class="card-header">
        <h3 class="card-title">Generate Blok Otomatis (Per Agama)</h3>
    </div>
    <form action="{{ route('admin.graves.generate-block') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem">
            <div class="form-group">
                <label class="form-label">Agama</label>
                <select name="religion" class="form-control" required>
                    <option value="islam">Islam</option>
                    <option value="protestan">Protestan</option>
                    <option value="katolik">Katolik</option>
                    <option value="hindu">Hindu</option>
                    <option value="budha">Budha</option>
                    <option value="konghucu">Konghucu</option>
                    <option value="umum">Umum</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Jumlah Makam (Kapasitas Blok)</label>
                <input type="number" name="amount" class="form-control" value="20" min="1" max="100" required>
            </div>
        </div>
        <div class="form-group" style="margin-top: 0.5rem; margin-bottom: 1.5rem;">
            <small style="color: var(--gray-600); display: flex; gap: 8px; align-items: flex-start;">
                <i data-lucide="info" style="width: 16px; flex-shrink: 0; color: var(--info);"></i>
                <span>Fitur ini otomatis membuat blok dengan kode A.1, B.1, dst. Syarat: Semua makam pada agama tersebut harus sudah <strong>Penuh/Terisi</strong>.</span>
            </small>
        </div>
        <button type="submit" class="btn btn-success"><i data-lucide="zap"></i> Generate Blok Baru</button>
        <button type="button" class="btn btn-secondary" onclick="toggleAutoGenerateForm()">Batal</button>
    </form>
</div>

<div class="card">
    <div class="card-header" style="display: flex; gap: 10px; flex-wrap: wrap;">
        <h3 class="card-title" style="flex-grow: 1;">Pengelolaan Data Makam</h3>
        <button class="btn btn-success" onclick="toggleAutoGenerateForm()"><i data-lucide="layers"></i> Tambah Blok Otomatis</button>
    </div>

<script>
    function toggleGraveForm() {
        var form = document.getElementById('addGraveForm');
        var editForm = document.getElementById('editGraveForm');
        var autoForm = document.getElementById('autoGenerateForm');
        editForm.style.display = 'none';
        if(autoForm) autoForm.style.display = 'none';
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    function toggleAutoGenerateForm() {
        var form = document.getElementById('addGraveForm');
        var editForm = document.getElementById('editGraveForm');
        var autoForm = document.getElementById('autoGenerateForm');
        form.style.display = 'none';
        editForm.style.display = 'none';
        autoForm.style.display = autoForm.style.display === 'none' ? 'block' : 'none';
    }

    function toggleEditGraveForm(id, block, number, religion, buried, heir, status) {
        var form = document.getElementById('addGraveForm');
        var editForm = document.getElementById('editGraveForm');
        var autoForm = document.getElementById('autoGenerateForm');
        form.style.display = 'none';
        if(autoForm) autoForm.style.display = 'none';
        
        if(id) {
            editForm.style.display = 'block';
            document.getElementById('editGraveAction').action = '/admin/graves/' + id;
            document.getElementById('edit_block_name').value = block;
            document.getElementById('edit_grave_number').value = number;
            document.getElementById('edit_religion').value = religion;
            document.getElementById('edit_buried_name').value = buried || '';
            document.getElementById('edit_heir_name').value = heir || '';
            document.getElementById('edit_status').value = status;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            editForm.style.display = 'none';
        }
    }

    function confirmDeleteGrave(id) {
        if(confirm('Apakah Anda yakin ingin menghapus data makam ini?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/graves/' + id;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
    <div style="overflow-x: auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Blok</th>
                    <th>No. Makam</th>
                    <th>Agama</th>
                    <th>Nama Almarhum</th>
                    <th>Ahli Waris</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($graves as $grave)
                <tr>
                    <td>{{ $grave->block_name }}</td>
                    <td>{{ $grave->grave_number }}</td>
                    <td><span class="badge" style="background: var(--gray-100)">{{ ucfirst($grave->religion) }}</span></td>
                    <td>{{ $grave->buried_name ?? '-' }}</td>
                    <td>
                        @if($grave->heir_name)
                        <div>
                            <strong>{{ $grave->heir_name }}</strong><br>
                            <small>{{ $grave->heir_contact }}</small>
                        </div>
                        @else
                        -
                        @endif
                    </td>
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
                    <td>
                        <button class="btn btn-secondary" style="padding: 0.4rem" onclick="toggleEditGraveForm('{{ $grave->id }}', '{{ $grave->block_name }}', '{{ $grave->grave_number }}', '{{ $grave->religion }}', '{{ $grave->buried_name }}', '{{ $grave->heir_name }}', '{{ $grave->status }}')">
                            <i data-lucide="edit-2" style="width: 16px; height: 16px"></i>
                        </button>
                        <button class="btn btn-danger" style="padding: 0.4rem" onclick="confirmDeleteGrave('{{ $grave->id }}')">
                            <i data-lucide="trash-2" style="width: 16px; height: 16px"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
