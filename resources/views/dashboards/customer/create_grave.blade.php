@extends('layouts.app', ['title' => 'Tambah / Pesan Makam'])

@section('content')
<style>
    .split-layout {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }
    .map-pane {
        flex: 1.2;
        min-width: 500px;
    }
    .form-pane {
        flex: 0.8;
        min-width: 350px;
    }

    /* Map Styles (Reused from Index) */
    .map-container-mini {
        position: relative;
        background: #84cc16;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        box-shadow: inset 0 0 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 4px solid #4d7c0f;
    }
    .map-layout-mini {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        transform: scale(0.85);
        transform-origin: top center;
    }
    .blocks-container-mini {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    .block-zone-mini {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 3px;
        padding: 6px;
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(2px);
    }
    .block-zone-title-mini {
        color: #fff;
        font-weight: 700;
        font-size: 0.75rem;
        margin-bottom: 0.3rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.6);
        background: rgba(0, 0, 0, 0.4);
        padding: 1px 6px;
        border-radius: 3px;
        text-align: center;
    }
    .plot-box-mini {
        width: 18px;
        height: 18px;
        border: 1px solid;
        border-radius: 2px;
        font-size: 0.4rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .tree-mini {
        position: absolute;
        width: 25px;
        height: 25px;
        background: #22c55e;
        border-radius: 50%;
        box-shadow: inset -3px -3px 8px rgba(0, 0, 0, 0.4);
        z-index: 5;
    }
    .road-mini {
        height: 20px;
        background: #64748b;
        position: relative;
    }
    .road-mini::after {
        content: '';
        width: 100%;
        height: 1px;
        background: repeating-linear-gradient(90deg, #fff, #fff 5px, transparent 5px, transparent 10px);
        position: absolute;
        top: 50%;
    }

    /* Form Styles */
    .optgroup-title {
        font-weight: 700;
        background: var(--gray-100);
        padding: 5px 10px;
        color: var(--primary);
        font-size: 0.85rem;
    }
    /* Highlight Animation */
    @keyframes block-glow {
        0% { box-shadow: 0 0 5px rgba(255, 255, 255, 0.5); }
        50% { box-shadow: 0 0 20px rgba(255, 255, 255, 0.8), inset 0 0 10px rgba(255,255,255,0.5); }
        100% { box-shadow: 0 0 5px rgba(255, 255, 255, 0.5); }
    }
    .highlighted-block {
        animation: block-glow 1.5s infinite;
        background: rgba(255, 255, 255, 0.5) !important;
        transform: scale(1.05);
        z-index: 10;
        transition: all 0.3s ease;
        border: 2px solid #fff;
    }

    /* Religion Colors */
    .rel-islam { background: #22c55e; border-color: #16a34a; color: #f0fdf4; }
    .rel-protestan { background: #3b82f6; border-color: #2563eb; color: #eff6ff; }
    .rel-katolik { background: #eab308; border-color: #ca8a04; color: #fefce8; }
    .rel-hindu { background: #a855f7; border-color: #9333ea; color: #faf5ff; }
    .rel-budha { background: #ef4444; border-color: #dc2626; color: #fef2f2; }
    .rel-konghucu { background: #06b6d4; border-color: #0891b2; color: #ecfeff; }
    .rel-umum { background: #64748b; border-color: #475569; color: #f8fafc; }

    .status-occupied-mini {
        position: relative;
        opacity: 0.95;
    }
    .status-occupied-mini::before {
        content: ''; 
        position: absolute;
        width: 10px;
        height: 12px;
        background-color: #111827; 
        border-radius: 4px 4px 1px 1px;
        opacity: 0.8;
        z-index: 0;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        border: 1px solid rgba(255,255,255,0.2);
    }
    .status-booked-mini {
        border-style: dashed !important;
        border-width: 2px !important;
    }
    
    @keyframes marker-pulse {
        0% { transform: translate(-50%, -50%) scale(1); box-shadow: 0 0 0 2px #fff, 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: translate(-50%, -50%) scale(1.1); box-shadow: 0 0 0 2px #fff, 0 0 0 6px rgba(239, 68, 68, 0); }
        100% { transform: translate(-50%, -50%) scale(1); box-shadow: 0 0 0 2px #fff, 0 0 0 0 rgba(239, 68, 68, 0); }
    }
</style>

<div class="split-layout">
    <!-- Left Pane: Map Preview -->
    <div class="map-pane">
        <div class="card h-100">
            <div class="card-header" style="display: flex; align-items: center; gap: 10px;">
                <h3 class="card-title" style="margin: 0;">Referensi Denah & Blok Makam</h3>
                <div style="position: relative; display: inline-block;">
                    <button id="chatbot-toggle" class="chatbot-btn-mini" style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: white; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        <i data-lucide="bot" style="width: 18px;"></i>
                    </button>
                    <span class="chatbot-tooltip-mini">Asisten AI</span>
                </div>
            </div>
            <style>
                .chatbot-btn-mini:hover { transform: scale(1.15) rotate(5deg); background: #404040; }
                .chatbot-tooltip-mini {
                    visibility: hidden; background-color: var(--gray-900); color: #fff; text-align: center; padding: 4px 10px; border-radius: 6px; position: absolute; z-index: 100; top: 40px; left: 50%; transform: translateX(-50%); opacity: 0; transition: opacity 0.3s, visibility 0.3s; font-size: 0.7rem; white-space: nowrap; font-weight: 600; box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                }
                .chatbot-tooltip-mini::after { content: ""; position: absolute; bottom: 100%; left: 50%; margin-left: -5px; border-width: 5px; border-style: solid; border-color: transparent transparent var(--gray-900) transparent; }
                div:hover > .chatbot-tooltip-mini { visibility: visible; opacity: 1; }
            </style>
            <div class="card-body">
                <div class="map-container-mini">
                    @php
                        $allGraves = \App\Models\Grave::all();
                        $blocksGrouped = $allGraves->groupBy('block_name')->values();
                    @endphp
                    
                    <div class="map-layout-mini">
                        @foreach($blocksGrouped->chunk(4) as $blockRow)
                            <div class="blocks-container-mini">
                                @foreach($blockRow as $blockIdx => $gravesInBlock)
                                    @php
                                        $firstGrave = $gravesInBlock->first();
                                        $religion = $firstGrave ? ($firstGrave->religion ?? 'umum') : 'umum';
                                        $colorClass = 'rel-' . strtolower($religion);
                                        $blockName = $firstGrave ? $firstGrave->block_name : 'Blok';
                                        $cleanBlockId = str_replace(' ', '_', $blockName);
                                    @endphp
                                    <div id="wrapper_{{ $cleanBlockId }}" style="transition: all 0.3s; padding: 5px; border-radius: 8px;">
                                        <div class="block-zone-title-mini">{{ $blockName }}</div>
                                        <div class="block-zone-mini">
                                            @foreach($gravesInBlock->take(20) as $grave)
                                                @php
                                                    $statusClass = '';
                                                    if ($grave->status == 'occupied') {
                                                        $statusClass = 'status-occupied-mini';
                                                    } elseif ($grave->status == 'booked') {
                                                        $statusClass = 'status-booked-mini';
                                                    }
                                                @endphp
                                                <div class="plot-box-mini {{ $colorClass }} {{ $statusClass }}" 
                                                     title="Makam {{ $grave->grave_number }} ({{ ucfirst($grave->status) }})"
                                                     style="cursor: pointer; position: relative;"
                                                     onclick="selectGraveMapping('{{ $grave->grave_number }}', '{{ $blockName }}', this, '{{ $grave->status }}')">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if(!$loop->last)
                                <div class="road-mini"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div style="margin-top: 1rem; font-size: 0.85rem; color: var(--gray-600);">
                    <p><i data-lucide="info" style="width: 14px; display: inline-block; vertical-align: middle;"></i> Tip: <strong>Klik pada kotak kecil</strong> makam untuk melihat lokasi detailnya di peta utama dengan penanda merah.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Pane: Booking Form -->
    <div class="form-pane">
        <div class="card h-100">
            <div class="card-header">
                <h3 class="card-title">Detail Pemesanan</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('customer.graves.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Blok Pilihan (Berdasarkan Agama)</label>
                        <select name="block_name" class="form-control" required style="height: auto; padding: 10px;">
                            <optgroup label="☪️ ISLAM">
                                <option value="Blok A">Blok A</option>
                                <option value="Blok B">Blok B</option>
                            </optgroup>
                            <optgroup label="✝️ PROTESTAN">
                                <option value="Blok C">Blok C</option>
                                <option value="Blok D">Blok D</option>
                            </optgroup>
                            <optgroup label="⛪ KATOLIK">
                                <option value="Blok E">Blok E</option>
                                <option value="Blok F">Blok F</option>
                            </optgroup>
                            <optgroup label="🕉️ HINDU">
                                <option value="Blok G">Blok G</option>
                                <option value="Blok H">Blok H</option>
                            </optgroup>
                            <optgroup label="☸️ BUDHA">
                                <option value="Blok I">Blok I</option>
                                <option value="Blok J">Blok J</option>
                            </optgroup>
                            <optgroup label="☯️ KONGHUCU">
                                <option value="Blok K">Blok K</option>
                                <option value="Blok L">Blok L</option>
                            </optgroup>
                            <optgroup label="🌐 UMUM">
                                <option value="Blok M">Blok M</option>
                                <option value="Blok N">Blok N</option>
                            </optgroup>
                        </select>
                        <small style="color: var(--primary); font-size: 0.75rem; display: block; mt: 2px;">* Agama akan otomatis menyesuaikan dengan blok yang dipilih.</small>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nomor Makam (Lihat Denah)</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="text" name="grave_number" class="form-control" placeholder="Contoh: A-012" required>
                            <a id="btn_preview_main" href="#" class="btn btn-secondary" style="display: none; align-items: center; gap: 5px; white-space: nowrap; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">
                                <i data-lucide="map-pin" style="width: 16px;"></i> Lihat di Peta Utama
                            </a>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Calon Almarhum (Opsional)</label>
                        <input type="text" name="buried_name" class="form-control" placeholder="Isi jika sudah ada">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Harga Pemesanan & Administrasi</label>
                        <div class="form-control" style="background: var(--gray-50); font-weight: 700; color: #166534; display: flex; align-items: center; gap: 8px;">
                            <i data-lucide="tag" style="width: 16px;"></i>
                            Rp 2.500.000
                        </div>
                        <small style="color: var(--gray-500); font-size: 0.7rem;">* Harga sudah termasuk retribusi makam untuk 8 bulan pertama.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Keterangan tambahan..."></textarea>
                    </div>

                    <div style="margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.8rem;">Konfirmasi Pemesanan</button>
                        <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary" style="width: 100%; margin-top: 0.5rem; text-align: center; display: block;">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const blockSelect = document.querySelector('select[name="block_name"]');
    
    blockSelect.addEventListener('change', function() {
        // Reset all highlights
        document.querySelectorAll('[id^="wrapper_"]').forEach(el => {
            el.classList.remove('highlighted-block');
        });

        // Add highlight to selected
        const blockId = this.value.replace(' ', '_');
        const target = document.getElementById('wrapper_' + blockId);
        if(target) {
            target.classList.add('highlighted-block');
            target.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
        }
    });

    // Initial check
    window.onload = () => {
        blockSelect.dispatchEvent(new Event('change'));
    };

    function selectGraveMapping(graveNumber, blockName, elem, status) {
        if(status !== 'available') {
            alert('Maaf, makam dengan status ' + status + ' tidak bisa dipesan atau sudah terisi.');
            return;
        }

        // Fill form select
        for (let i = 0; i < blockSelect.options.length; i++) {
            if (blockSelect.options[i].value === blockName) {
                blockSelect.selectedIndex = i;
                blockSelect.dispatchEvent(new Event('change'));
                break;
            }
        }
        
        // Fill grave number
        document.querySelector('input[name="grave_number"]').value = graveNumber;

        // Clear previous markers
        document.querySelectorAll('.plot-box-mini').forEach(el => {
            el.classList.remove('selected-plot');
            const marker = el.querySelector('.plot-marker');
            if (marker) marker.remove();
        });

        // Add visual marker (PIN icon)
        elem.classList.add('selected-plot');
        elem.innerHTML = '<div class="plot-marker" style="position: absolute; top: -5px; left: 50%; transform: translateX(-50%); font-size: 1rem; z-index: 20; filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));">📍</div>';
        
        // Show "See on Main Map" button
        const btnPreview = document.getElementById('btn_preview_main');
        if (btnPreview) {
            btnPreview.style.display = 'flex';
            btnPreview.href = "{{ route('customer.dashboard') }}?highlight=" + encodeURIComponent(graveNumber);
        }
        lucide.createIcons();
    }
</script>
@endsection
