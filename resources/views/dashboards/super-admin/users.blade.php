@extends('layouts.app', ['title' => 'Kelola Pengguna'])

@section('content')
    <div class="card" id="addUserForm" style="display: none; margin-bottom: 2rem">
        <div class="card-header">
            <h3 class="card-title">Tambah Pengguna Baru</h3>
        </div>
        <form action="{{ route('super-admin.users.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Bibih Aldiansyah" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="•••@gmail.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="customer">Customer</option>
                        <option value="karyawan">Karyawan</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan User</button>
            <button type="button" class="btn btn-secondary" onclick="toggleUserForm()">Batal</button>
        </form>
    </div>

    <div class="card" id="editUserForm" style="display: none; margin-bottom: 2rem">
        <div class="card-header">
            <h3 class="card-title">Edit Pengguna</h3>
        </div>
        <form id="editUserAction" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="edit_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="edit_email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" id="edit_role" class="form-control" required>
                        <option value="customer">Customer</option>
                        <option value="karyawan">Karyawan</option>
                        <option value="admin">Admin</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control" placeholder="••••">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
            <button type="button" class="btn btn-secondary" onclick="toggleEditForm()">Batal</button>
        </form>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Semua Pengguna</h3>
            <button class="btn btn-primary" onclick="toggleUserForm()"><i data-lucide="plus"></i> Tambah Pengguna</button>
        </div>

        <script>
            function toggleUserForm() {
                var form = document.getElementById('addUserForm');
                var editForm = document.getElementById('editUserForm');
                editForm.style.display = 'none';
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }

            function toggleEditForm(id, name, email, role) {
                var form = document.getElementById('addUserForm');
                var editForm = document.getElementById('editUserForm');
                form.style.display = 'none';
                
                if(id) {
                    editForm.style.display = 'block';
                    document.getElementById('editUserAction').action = '/super-admin/users/' + id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_email').value = email;
                    document.getElementById('edit_role').value = role;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    editForm.style.display = 'none';
                }
            }

            function confirmDelete(id) {
                if(confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')) {
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/super-admin/users/' + id;
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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Dibuat Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem">
                                    <div class="user-img" style="width: 32px; height: 32px; background-size: cover; background-position: center; {{ $user->photo ? 'background-image: url(/storage/'.$user->photo.')' : '' }}"></div>
                                    {{ $user->name }}
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge" style="background: var(--gray-200); color: var(--gray-800)">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                <button class="btn btn-secondary" style="padding: 0.4rem" onclick="toggleEditForm('{{ $user->id }}', '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}')">
                                    <i data-lucide="edit-2" style="width: 16px; height: 16px"></i>
                                </button>
                                <button class="btn btn-danger" style="padding: 0.4rem" onclick="confirmDelete('{{ $user->id }}')">
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