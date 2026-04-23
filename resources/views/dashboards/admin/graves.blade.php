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

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <h3 class="card-title">Pengelolaan Data Makam</h3>
        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
            <div style="position: relative;">
                <i data-lucide="search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--gray-400);"></i>
                <input type="text" id="graveSearch" placeholder="Cari data makam..." class="form-control" style="padding-left: 30px; width: 220px; font-size: 0.85rem; height: 38px;">
            </div>
            <button class="btn btn-primary" onclick="toggleGraveForm()" style="height: 38px; display: flex; align-items: center; gap: 6px; padding: 0 1rem;">
                <i data-lucide="plus" style="width: 16px;"></i> Tambah Makam
            </button>
        </div>
    </div>

<script>
    function toggleGraveForm() {
        var form = document.getElementById('addGraveForm');
        var editForm = document.getElementById('editGraveForm');
        editForm.style.display = 'none';
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }

    function toggleEditGraveForm(id, block, number, religion, buried, heir, status) {
        var form = document.getElementById('addGraveForm');
        var editForm = document.getElementById('editGraveForm');
        form.style.display = 'none';
        
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

    // Client-side Search Logic
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('graveSearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const query = this.value.toLowerCase();
                const rows = document.querySelectorAll('#graveTable tbody tr');
                
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }
    });
</script>
    <div style="overflow-x: auto">
        <table class="table" id="graveTable">
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
                    <td><span class="badge badge-{{ $grave->status }}">{{ $grave->status }}</span></td>
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
