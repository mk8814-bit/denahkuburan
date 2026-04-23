@extends('layouts.app', ['title' => 'Jadwal Pemeliharaan'])

@section('content')
<div class="card" id="addMaintenanceForm" style="display: none; margin-bottom: 2rem">
    <div class="card-header">
        <h3 class="card-title">Tambah Jadwal Pemeliharaan</h3>
    </div>
    <form action="{{ route('admin.maintenance.store') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem">
            <div class="form-group">
                <label class="form-label">Nama Blok / Area</label>
                <select name="block_name" id="formBlockSelect" class="form-control" required onchange="previewBlock(this.value)">
                    <option value="">-- Pilih Blok --</option>
                    @foreach($graves->pluck('block_name')->unique()->sort() as $block)
                        <option value="{{ $block }}">{{ $block }}</option>
                    @endforeach
                </select>
            </div>
            <!-- Live Map Preview -->
            <div class="form-group" id="formMapPreview" style="display: none; grid-column: span 2;">
                <label class="form-label" style="display: flex; align-items: center; gap: 6px; font-size: 0.85rem;">
                    <i data-lucide="map" style="width: 14px;"></i> Preview Denah <span id="formMapBlockName" style="font-weight: 800; color: var(--primary);"></span>
                </label>
                <div style="max-width: 320px; background: #82c91e; border-radius: 10px; padding: 12px; border: 2px solid #5c940d; box-shadow: inset 0 0 30px rgba(0,0,0,0.06);">
                    <div id="formMapGrid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 4px;"></div>
                </div>
                <div style="margin-top: 0.4rem; display: flex; gap: 8px; flex-wrap: wrap; font-size: 0.65rem; color: var(--gray-500);">
                    <span style="display: flex; align-items: center; gap: 3px;"><span style="width: 8px; height: 8px; border-radius: 2px; background: #adb5bd; border: 1px solid #868e96; display: inline-block;"></span> Tersedia</span>
                    <span style="display: flex; align-items: center; gap: 3px;"><span style="width: 8px; height: 8px; border-radius: 2px; background: #212529; border: 1px solid #000; display: inline-block;"></span> Terisi</span>
                    <span style="display: flex; align-items: center; gap: 3px;"><span style="width: 8px; height: 8px; border-radius: 2px; border: 2px dashed #f59e0b; background: #fffbeb; display: inline-block;"></span> Dipesan</span>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Tanggal Pelaksanaan</label>
                <input type="date" name="scheduled_date" class="form-control" required>
            </div>
            <div class="form-group" style="grid-column: span 2">
                <label class="form-label">Deskripsi Pekerjaan</label>
                <input type="text" name="description" class="form-control" placeholder="Contoh: Pemotongan rumput berkala" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="dijadwalkan">Dijadwalkan</option>
                    <option value="sedang_dikerjakan">Sedang Dikerjakan</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan Tambahan</label>
                <textarea name="notes" class="form-control" rows="1"></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
        <button type="button" class="btn btn-secondary" onclick="toggleMaintForm()">Batal</button>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Jadwal Pemeliharaan Makam</h3>
        <button class="btn btn-primary" onclick="toggleMaintForm()"><i data-lucide="plus"></i> Tambah Jadwal</button>
    </div>
    <div style="overflow-x: auto">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Blok / Area</th>
                    <th>Pekerjaan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($maintenances as $maint)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($maint->scheduled_date)->format('d M Y') }}</td>
                    <td>
                        <a href="javascript:void(0)" onclick="showBlockMap('{{ $maint->block_name }}')" style="color: var(--primary); font-weight: 700; text-decoration: underline; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                            <i data-lucide="map-pin" style="width: 14px; height: 14px;"></i> {{ $maint->block_name }}
                        </a>
                    </td>
                    <td>{{ $maint->description }}</td>
                    <td>
                        @php
                            $badgeColor = '#64748b';
                            $statusText = ucfirst(str_replace('_', ' ', $maint->status));
                            if($maint->status === 'sedang_dikerjakan') {
                                $badgeColor = '#3b82f6';
                                $statusText = 'Sedang Dibersihkan / Maintenance';
                            }
                            if($maint->status === 'selesai') {
                                $badgeColor = '#10b981';
                            }
                        @endphp
                        <span class="badge" style="background: {{ $badgeColor }}; color: white; padding: 5px 10px; border-radius: 20px;">
                            <i data-lucide="{{ $maint->status === 'sedang_dikerjakan' ? 'loader' : ($maint->status === 'selesai' ? 'check-circle' : 'calendar') }}" style="width: 12px; vertical-align: middle; margin-right: 4px;"></i>
                            {{ $statusText }}
                        </span>
                    </td>
                    <td style="display: flex; gap: 0.3rem; align-items: center;">
                        @if($maint->status === 'dijadwalkan')
                            <button class="btn" style="padding: 0.4rem; background-color: #f59e0b; color: white; border: none; border-radius: 6px;" onclick="markMaintProgress('{{ $maint->id }}')" title="Mulai Maintenance">
                                <i data-lucide="wrench" style="width: 16px; height: 16px"></i>
                            </button>
                        @endif
                        @if($maint->status !== 'selesai')
                            <button class="btn" style="padding: 0.4rem; background-color: #10b981; color: white; border: none; border-radius: 6px;" onclick="markMaintComplete('{{ $maint->id }}')" title="Tandai Selesai">
                                <i data-lucide="check" style="width: 16px; height: 16px"></i>
                            </button>
                        @endif
                        <button class="btn" style="padding: 0.4rem; background-color: #ef4444; color: white; border: none; border-radius: 6px;" onclick="confirmDeleteMaint('{{ $maint->id }}')" title="Hapus">
                            <i data-lucide="trash-2" style="width: 16px; height: 16px"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 2rem; color: var(--gray-500)">Belum ada jadwal pemeliharaan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Block Map Modal -->
<div id="blockMapOverlay" onclick="closeBlockMap()" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 998; backdrop-filter: blur(3px);"></div>
<div id="blockMapModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999; background: white; border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,0.35); width: 550px; max-width: 95vw; max-height: 85vh; overflow-y: auto;">
    <div style="background: linear-gradient(135deg, #2b8a3e, #40c057); color: white; padding: 1.25rem 1.5rem; border-radius: 20px 20px 0 0; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 1.1rem; display: flex; align-items: center; gap: 8px;">
            <i data-lucide="map" style="width: 20px;"></i> Denah <span id="blockMapTitle"></span>
        </h3>
        <button onclick="closeBlockMap()" style="border: none; background: rgba(255,255,255,0.2); border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: white;"><i data-lucide="x" style="width: 16px;"></i></button>
    </div>
    <div style="padding: 1.5rem;">
        <div id="blockMapContent" style="background: #82c91e; border-radius: 16px; padding: 25px; border: 3px solid #5c940d; box-shadow: inset 0 0 50px rgba(0,0,0,0.08);">
            <div id="blockMapGrid" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px;"></div>
        </div>
        <div style="margin-top: 1rem; display: flex; gap: 12px; flex-wrap: wrap; font-size: 0.75rem; color: var(--gray-600);">
            <div style="display: flex; align-items: center; gap: 4px;"><div style="width: 14px; height: 14px; border-radius: 3px; background: #adb5bd; border: 1px solid #868e96;"></div> Tersedia</div>
            <div style="display: flex; align-items: center; gap: 4px;"><div style="width: 14px; height: 14px; border-radius: 3px; background: #212529; border: 1px solid #000;"></div> Terisi</div>
            <div style="display: flex; align-items: center; gap: 4px;"><div style="width: 14px; height: 14px; border-radius: 3px; border: 2px dashed #f59e0b; background: #fffbeb;"></div> Dipesan</div>
        </div>
    </div>
</div>

<script>
    // Graves data for map rendering
    const gravesData = @json($graves->groupBy('block_name'));
    const maintBlocks = @json($maintenances->where('status', '!=', 'selesai')->pluck('block_name')->unique()->values());

    function showBlockMap(blockName) {
        document.getElementById('blockMapTitle').innerText = blockName;
        const grid = document.getElementById('blockMapGrid');
        grid.innerHTML = '';

        const blockGraves = gravesData[blockName] || [];

        if (blockGraves.length === 0) {
            grid.style.display = 'block';
            grid.innerHTML = '<div style="text-align: center; padding: 2rem; color: white; font-weight: 600; opacity: 0.8;"><i data-lucide="alert-circle" style="width: 32px; height: 32px; margin-bottom: 8px; display: block; margin: 0 auto 8px;"></i>Belum ada data makam di blok ini.</div>';
        } else {
            grid.style.display = 'grid';
            blockGraves.forEach(function(grave) {
                const plot = document.createElement('div');

                // Religion color
                const relColors = {
                    'islam': { bg: '#fee101', border: '#fcc419', color: '#826a00' },
                    'protestan': { bg: '#4dabf7', border: '#339af0', color: '#004b82' },
                    'katolik': { bg: '#fab005', border: '#f08c00', color: '#824b00' },
                    'hindu': { bg: '#be4bdb', border: '#ae3ec9', color: '#4b0082' },
                    'budha': { bg: '#ff8787', border: '#fa5252', color: '#820000' },
                    'konghucu': { bg: '#74c0fc', border: '#4dabf7', color: '#003e82' },
                    'umum': { bg: '#adb5bd', border: '#868e96', color: '#343a40' }
                };
                const rel = (grave.religion || 'umum').toLowerCase();
                const rc = relColors[rel] || relColors['umum'];

                plot.style.cssText = `width: 100%; aspect-ratio: 1; border-radius: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 700; cursor: default; transition: transform 0.15s; position: relative;`;

                if (grave.status === 'occupied') {
                    plot.style.background = '#212529';
                    plot.style.border = '2px solid #000';
                    plot.style.color = '#fff';
                    plot.innerHTML = '<span style="font-size: 1.2rem;">🪦</span>';
                    plot.title = grave.grave_number + ' — ' + (grave.buried_name || 'Terisi');
                } else if (grave.status === 'booked') {
                    plot.style.background = rc.bg;
                    plot.style.border = '2px dashed ' + rc.border;
                    plot.style.color = rc.color;
                    plot.innerHTML = '<span>' + grave.grave_number + '</span><span style="font-size: 0.5rem; opacity: 0.7;">Dipesan</span>';
                    plot.title = grave.grave_number + ' — Dipesan';
                } else {
                    plot.style.background = rc.bg;
                    plot.style.border = '2px solid ' + rc.border;
                    plot.style.color = rc.color;
                    plot.innerHTML = '<span>' + grave.grave_number + '</span><span style="font-size: 0.5rem; opacity: 0.7;">' + rel.charAt(0).toUpperCase() + rel.slice(1) + '</span>';
                    plot.title = grave.grave_number + ' — Tersedia';
                }

                // Add maintenance ribbon if the block is under maintenance
                if (maintBlocks.includes(blockName)) {
                    const ribbon = document.createElement('div');
                    ribbon.style.cssText = 'position: absolute; top: -6px; right: -6px; background: #f59e0b; color: white; font-size: 0.45rem; padding: 1px 4px; border-radius: 3px; font-weight: 800; z-index: 5; box-shadow: 0 1px 3px rgba(0,0,0,0.2);';
                    ribbon.textContent = '🔧';
                    plot.appendChild(ribbon);
                }

                grid.appendChild(plot);
            });
        }

        document.getElementById('blockMapOverlay').style.display = 'block';
        document.getElementById('blockMapModal').style.display = 'block';
        lucide.createIcons();
    }

    function closeBlockMap() {
        document.getElementById('blockMapOverlay').style.display = 'none';
        document.getElementById('blockMapModal').style.display = 'none';
    }

    function previewBlock(blockName) {
        const preview = document.getElementById('formMapPreview');
        const grid = document.getElementById('formMapGrid');
        const title = document.getElementById('formMapBlockName');

        if (!blockName) {
            preview.style.display = 'none';
            return;
        }

        title.innerText = blockName;
        grid.innerHTML = '';

        const blockGraves = gravesData[blockName] || [];

        if (blockGraves.length === 0) {
            grid.style.display = 'block';
            grid.innerHTML = '<div style="text-align: center; padding: 1.5rem; color: white; font-weight: 600; opacity: 0.8;">Belum ada data makam di blok ini.</div>';
        } else {
            grid.style.display = 'grid';
            blockGraves.forEach(function(grave) {
                const plot = document.createElement('div');

                const relColors = {
                    'islam': { bg: '#fee101', border: '#fcc419', color: '#826a00' },
                    'protestan': { bg: '#4dabf7', border: '#339af0', color: '#004b82' },
                    'katolik': { bg: '#fab005', border: '#f08c00', color: '#824b00' },
                    'hindu': { bg: '#be4bdb', border: '#ae3ec9', color: '#4b0082' },
                    'budha': { bg: '#ff8787', border: '#fa5252', color: '#820000' },
                    'konghucu': { bg: '#74c0fc', border: '#4dabf7', color: '#003e82' },
                    'umum': { bg: '#adb5bd', border: '#868e96', color: '#343a40' }
                };
                const rel = (grave.religion || 'umum').toLowerCase();
                const rc = relColors[rel] || relColors['umum'];

                plot.style.cssText = 'width: 100%; aspect-ratio: 1; border-radius: 6px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: 700; position: relative;';

                if (grave.status === 'occupied') {
                    plot.style.background = '#212529';
                    plot.style.border = '2px solid #000';
                    plot.innerHTML = '<span style="font-size: 1rem;">🪦</span>';
                    plot.title = grave.grave_number + ' — ' + (grave.buried_name || 'Terisi');
                } else if (grave.status === 'booked') {
                    plot.style.background = rc.bg;
                    plot.style.border = '2px dashed ' + rc.border;
                    plot.style.color = rc.color;
                    plot.innerHTML = '<span>' + grave.grave_number + '</span><span style="font-size: 0.45rem; opacity: 0.7;">Dipesan</span>';
                    plot.title = grave.grave_number + ' — Dipesan';
                } else {
                    plot.style.background = rc.bg;
                    plot.style.border = '2px solid ' + rc.border;
                    plot.style.color = rc.color;
                    plot.innerHTML = '<span>' + grave.grave_number + '</span><span style="font-size: 0.45rem; opacity: 0.7;">' + rel.charAt(0).toUpperCase() + rel.slice(1) + '</span>';
                    plot.title = grave.grave_number + ' — Tersedia';
                }

                grid.appendChild(plot);
            });
        }

        preview.style.display = 'block';
        lucide.createIcons();
    }

    function toggleMaintForm() {
        var form = document.getElementById('addMaintenanceForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
        if(form.style.display === 'block') window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function confirmDeleteMaint(id) {
        if(confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/maintenance/' + id;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }

    function markMaintProgress(id) {
        if(confirm('Tandai pekerjaan ini sedang dalam proses maintenance?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/maintenance/' + id + '/progress';
            form.innerHTML = '@csrf @method("PATCH")';
            document.body.appendChild(form);
            form.submit();
        }
    }

    function markMaintComplete(id) {
        if(confirm('Tandai pekerjaan pemeliharaan ini sebagai selesai?')) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/maintenance/' + id + '/complete';
            form.innerHTML = '@csrf @method("PATCH")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
@endsection
