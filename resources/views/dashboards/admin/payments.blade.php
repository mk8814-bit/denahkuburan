@extends('layouts.app', ['title' => 'Konfirmasi Pembayaran'])

@section('content')
<div class="card" id="editPaymentForm" style="display: none; margin-bottom: 2rem">
    <div class="card-header">
        <h3 class="card-title">Edit Pembayaran</h3>
    </div>
    <form id="editPaymentAction" method="POST">
        @csrf
        @method('PUT')
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem">
            <div class="form-group">
                <label class="form-label">Jumlah (Rp)</label>
                <input type="number" name="amount" id="edit_amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Bayar</label>
                <input type="date" name="payment_date" id="edit_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" id="edit_status" class="form-control" required>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <input type="text" name="notes" id="edit_notes" class="form-control">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Pembayaran</button>
        <button type="button" class="btn btn-secondary" onclick="toggleEditPaymentForm()">Batal</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Pembayaran Masuk</h3>
    </div>
    <div style="overflow-x: auto">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Makam & Agama</th>
                    <th>Bukti</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->user->name }}</td>
                    <td>
                        @if($payment->grave)
                            <strong>{{ $payment->grave->grave_number }} ({{ $payment->grave->block_name }})</strong><br>
                            <small class="badge" style="background: var(--gray-100); color: var(--gray-700)">{{ ucfirst($payment->grave->religion) }}</small>
                        @else
                            <span style="color: var(--danger); font-size: 0.8rem;">[Makam Terhapus]</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->proof)
                        <button class="btn btn-secondary" style="padding: 0.3rem" onclick="viewProof('/storage/{{ $payment->proof }}')">
                            <i data-lucide="image" style="width: 16px; height: 16px"></i> Lihat
                        </button>
                        @else
                        -
                        @endif
                    </td>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td><span class="badge badge-{{ $payment->status }}">{{ $payment->status }}</span></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem">
                            @if($payment->status === 'pending')
                            <form action="{{ route('admin.payments.confirm', $payment->id) }}" method="POST" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-success" style="padding: 0.4rem" title="Konfirmasi">
                                    <i data-lucide="check-circle" style="width: 16px; height: 16px"></i>
                                </button>
                            </form>
                            @endif
                            <button class="btn btn-secondary" style="padding: 0.4rem" onclick="toggleEditPaymentForm('{{ $payment->id }}', '{{ $payment->amount }}', '{{ $payment->payment_date }}', '{{ $payment->status }}', '{{ $payment->notes }}')" title="Edit">
                                <i data-lucide="edit-2" style="width: 16px; height: 16px"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 0.4rem" onclick="confirmDeletePayment('{{ $payment->id }}')" title="Hapus">
                                <i data-lucide="trash-2" style="width: 16px; height: 16px"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--gray-500)">Tidak ada data pembayaran.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for viewing proof image -->
<div id="imageModal" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); align-items:center; justify-content:center;">
    <div style="position:relative; max-width:80%; max-height:80%;">
        <span onclick="closeModal()" style="position:absolute; top:-40px; right:0; color:white; font-size:30px; cursor:pointer;">&times;</span>
        <img id="modalImg" src="" style="width:100%; height:auto; border-radius:8px; border:4px solid white;">
    </div>
</div>

<script>
    function viewProof(url) {
        document.getElementById('modalImg').src = url;
        document.getElementById('imageModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    function toggleEditPaymentForm(id, amount, date, status, notes) {
        var editForm = document.getElementById('editPaymentForm');
        
        if(id) {
            editForm.style.display = 'block';
            document.getElementById('editPaymentAction').action = '/admin/payments/' + id;
            document.getElementById('edit_amount').value = amount;
            document.getElementById('edit_date').value = date;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_notes').value = notes || '';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            editForm.style.display = 'none';
        }
    }

    function confirmDeletePayment(id) {
        if(confirm('Apakah Anda yakin ingin menghapus data pembayaran ini?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("/admin/payments") }}/' + id;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection

