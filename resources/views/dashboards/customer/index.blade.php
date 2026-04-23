@extends('layouts.app', ['title' => 'Dashboard Customer & Peta Denah Makam'])

@section('content')
<style>
    :root {
        --map-grass: #82c91e;
        --map-grass-dark: #5c940d;
        --map-road: #495057;
    }

    /* Map Layout */
    .cemetery-map-container {
        background: var(--map-grass);
        border: 4px solid var(--map-grass-dark);
        border-radius: 20px;
        padding: 30px 20px;
        position: relative;
        overflow: hidden;
        box-shadow: inset 0 0 100px rgba(0,0,0,0.1);
    }

    .map-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        background: rgba(255,255,255,0.9);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        backdrop-filter: blur(5px);
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    /* Blocks & Plots */
    .blocks-row {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 15px;
        z-index: 10;
        position: relative;
        flex-wrap: wrap;
    }

    .block-unit {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px;
        border-radius: 10px;
        text-align: center;
        flex-shrink: 0;
    }

    .block-name-label {
        background: #2b8a3e;
        color: white;
        padding: 2px 12px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.75rem;
        display: inline-block;
        margin-bottom: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .plot-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 4px;
    }

    /* Plot Styles */
    .plot-cell {
        width: 42px;
        height: 42px;
        border-radius: 4px;
        border: 2px solid rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
    }

    .plot-cell:hover {
        transform: scale(1.15);
        z-index: 20;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .plot-religion-label { opacity: 0.7; font-size: 0.45rem; }
    .plot-number-label { line-height: 1; font-size: 0.65rem; }
    .plot-sub-label { font-size: 0.45rem; margin-top: 1px; }

    /* Religion Colors */
    .rel-islam { background: #22c55e; border-color: #16a34a; color: #f0fdf4; }
    .rel-protestan { background: #3b82f6; border-color: #2563eb; color: #eff6ff; }
    .rel-katolik { background: #eab308; border-color: #ca8a04; color: #fefce8; }
    .rel-hindu { background: #a855f7; border-color: #9333ea; color: #faf5ff; }
    .rel-budha { background: #ef4444; border-color: #dc2626; color: #fef2f2; }
    .rel-konghucu { background: #06b6d4; border-color: #0891b2; color: #ecfeff; }
    .rel-umum { background: #64748b; border-color: #475569; color: #f8fafc; }

    /* Status Overlays */
    .st-booked { border-style: dashed !important; border-width: 2px; }
    .st-occupied { background: #212529 !important; border-color: #000; color: #fff !important; }
    .st-occupied::after {
        content: '🪦';
        font-size: 1.1rem;
        position: absolute;
    }
    .st-occupied .plot-number-label,
    .st-occupied .plot-religion-label,
    .st-occupied .plot-sub-label {
        opacity: 0;
    }

    /* Maintenance Style */
    .in-maintenance {
        border: 3px dashed #f59e0b !important;
        animation: pulse-maint 2s infinite;
    }
    @keyframes pulse-maint {
        0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
        100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
    }

    .maint-tag {
        position: absolute;
        top: -6px;
        left: -6px;
        background: #f59e0b;
        color: white;
        font-size: 0.45rem;
        padding: 1px 4px;
        border-radius: 3px;
        z-index: 21;
        font-weight: 800;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    /* Highlight Target Style */
    .highlight-target {
        border: 3px solid #ef4444 !important;
        box-shadow: 0 0 20px rgba(239, 68, 68, 0.8) !important;
        animation: pulse-red 1s infinite !important;
        z-index: 30 !important;
    }

    @keyframes pulse-red {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        50% { transform: scale(1.15); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }

    .highlight-target::after {
        content: '📍';
        position: absolute;
        top: -20px;
        font-size: 1.2rem;
        z-index: 31;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));
    }

    /* Road & Decorations */
    .road-strip {
        height: 50px;
        background: var(--map-road);
        position: relative;
        margin: 20px -20px;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 5;
    }

    .road-strip::before {
        content: '';
        width: 100%;
        height: 2px;
        border-top: 4px dashed rgba(255,255,255,0.5);
    }

    .tree-deco {
        position: absolute;
        width: 35px;
        height: 35px;
        background: radial-gradient(circle at 30% 30%, #40c057, #2b8a3e);
        border-radius: 50%;
        box-shadow: 0 6px 12px rgba(0,0,0,0.25);
        z-index: 15;
    }

    /* Legend */
    .map-legend-bar {
        margin-top: 15px;
        background: rgba(255,255,255,0.92);
        padding: 10px 15px;
        border-radius: 10px;
        font-size: 0.7rem;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .legend-entry { display: flex; align-items: center; gap: 5px; font-weight: 500; }
    .legend-swatch { width: 14px; height: 14px; border-radius: 3px; border: 1px solid rgba(0,0,0,0.1); }
</style>

<div class="card" style="margin-bottom: 2rem;">
    <div class="card-header">
        <h3 class="card-title" style="margin: 0;">Peta Denah Makam</h3>
    </div>
    <div class="card-body" style="padding: 0;">

        <div class="cemetery-map-container">
            <div class="map-header">
                <h3 style="margin: 0; font-weight: 800; color: var(--primary); font-size: 1.1rem; display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="map" style="width: 20px;"></i> Denah Makam Digital
                    <div style="position: relative; display: inline-block; margin-left: 5px;">
                        <button id="chatbot-toggle" class="chatbot-btn-mini" style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary); color: white; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            <i data-lucide="bot" style="width: 18px;"></i>
                        </button>
                        <span class="chatbot-tooltip-mini">Asisten AI</span>
                    </div>
                </h3>

                <style>
                    .chatbot-btn-mini:hover {
                        transform: scale(1.15) rotate(5deg);
                        background: #404040;
                    }
                    .chatbot-tooltip-mini {
                        visibility: hidden;
                        background-color: var(--gray-900);
                        color: #fff;
                        text-align: center;
                        padding: 4px 10px;
                        border-radius: 6px;
                        position: absolute;
                        z-index: 100;
                        top: 40px;
                        left: 50%;
                        transform: translateX(-50%);
                        opacity: 0;
                        transition: opacity 0.3s, visibility 0.3s;
                        font-size: 0.7rem;
                        white-space: nowrap;
                        font-weight: 600;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    }
                    .chatbot-tooltip-mini::after {
                        content: "";
                        position: absolute;
                        bottom: 100%;
                        left: 50%;
                        margin-left: -5px;
                        border-width: 5px;
                        border-style: solid;
                        border-color: transparent transparent var(--gray-900) transparent;
                    }
                    div:hover > .chatbot-tooltip-mini {
                        visibility: visible;
                        opacity: 1;
                    }
                </style>
                <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                    <select class="form-control" style="width: auto; padding: 4px 10px;" id="religionFilter" onchange="filterMap()">
                        <option value="all">-- Semua Agama --</option>
                        <option value="islam">Islam</option>
                        <option value="protestan">Protestan</option>
                        <option value="katolik">Katolik</option>
                        <option value="hindu">Hindu</option>
                        <option value="budha">Budha</option>
                        <option value="konghucu">Konghucu</option>
                        <option value="umum">Umum</option>
                    </select>
                    <div style="background: linear-gradient(135deg, #2b8a3e, #40c057); color: white; padding: 6px 16px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; box-shadow: 0 4px 10px rgba(43, 138, 62, 0.3); display: flex; align-items: center; gap: 6px;">
                        <i data-lucide="mouse-pointer-click" style="width: 16px;"></i> Klik makam untuk detail
                    </div>
                </div>
            </div>

            @php
                $allGraves = \App\Models\Grave::all();
                $blocks = $allGraves->groupBy('block_name');
                $blockKeys = $blocks->keys()->sort()->values();

                // Split blocks into rows of 4
                $rows = $blockKeys->chunk(4);
                $userName = strtolower(trim(auth()->user()->name));
            @endphp

            @foreach($rows as $rowIdx => $blockRow)
                <div class="blocks-row">
                    @foreach($blockRow as $blockName)
                        <div class="block-unit">
                            <div class="block-name-label">{{ $blockName }}</div>
                            <div class="plot-grid">
                                @foreach($blocks[$blockName] as $grave)
                                    @php
                                        $religion = $grave->religion ?? 'umum';
                                        $relClass = 'rel-' . strtolower($religion);
                                        $statusClass = '';
                                        if ($grave->status === 'occupied') $statusClass = 'st-occupied';
                                        elseif ($grave->status === 'booked') $statusClass = 'st-booked';

                                        $isMyGrave = (strtolower(trim($grave->heir_name ?? '')) === $userName);
                                        $isMaint = in_array($grave->block_name, $maintenance_blocks ?? []);

                                        $numParts = explode('-', $grave->grave_number);
                                        $displayNum = count($numParts) > 1 ? $numParts[1] : $grave->grave_number;
                                        $relShort = strtoupper(substr($religion, 0, 3));
                                        $blockLetter = substr($blockName, -1);
                                    @endphp
                                    <div class="plot-cell {{ $relClass }} {{ $statusClass }} {{ $isMyGrave ? 'my-grave' : '' }} {{ $isMaint ? 'in-maintenance' : '' }}"
                                         id="plot-{{ str_replace(' ', '-', $grave->grave_number) }}"
                                         data-religion="{{ strtolower($religion) }}"
                                         onclick="showDetail('{{ $grave->grave_number }}', '{{ addslashes($grave->buried_name ?? '') }}', '{{ $grave->status }}', '{{ ucfirst($religion) }}', '{{ addslashes($grave->heir_name ?? '') }}', '{{ $grave->burial_date ?? '' }}', {{ $isMaint ? 'true' : 'false' }})"
                                         title="{{ $grave->grave_number }} - {{ ucfirst($religion) }} - {{ ucfirst($grave->status) }}">
                                        
                                        @if($isMaint)
                                            <div class="maint-tag">MAINT</div>
                                        @endif
                                        
                                        <span class="plot-religion-label">{{ $relShort }}</span>
                                        <span class="plot-number-label">{{ $displayNum }}</span>
                                        <div class="plot-sub-label">{{ $blockLetter }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @if(!$loop->last)
                    <!-- Road separator -->
                    <div class="road-strip">
                        <div class="tree-deco" style="left: 8%; top: -18px;"></div>
                        <div class="tree-deco" style="left: 30%; top: 28px;"></div>
                        <div class="tree-deco" style="left: 55%; top: -12px;"></div>
                        <div class="tree-deco" style="left: 80%; top: 32px;"></div>
                    </div>
                @endif
            @endforeach

            <!-- Legend -->
            <div class="map-legend-bar">
                <div class="legend-entry"><div class="legend-swatch rel-islam"></div> Islam</div>
                <div class="legend-entry"><div class="legend-swatch rel-protestan"></div> Protestan</div>
                <div class="legend-entry"><div class="legend-swatch rel-katolik"></div> Katolik</div>
                <div class="legend-entry"><div class="legend-swatch rel-hindu"></div> Hindu</div>
                <div class="legend-entry"><div class="legend-swatch rel-budha"></div> Budha</div>
                <div class="legend-entry"><div class="legend-swatch rel-konghucu"></div> Konghucu</div>
                <div class="legend-entry"><div class="legend-swatch rel-umum"></div> Umum</div>
                <div class="legend-entry"><div class="legend-swatch" style="background: #212529; border: 1px solid #000;"></div> Terisi</div>
                <div class="legend-entry"><div class="legend-swatch" style="border: 2px dashed #333;"></div> Dipesan</div>
                <div class="legend-entry"><div class="legend-swatch my-grave" style="width:14px; height:14px; animation:none; border: 3px solid #16a34a; background: #dcfce7;"></div> Milik Anda</div>
            </div>
        </div>

    </div>
</div>

<!-- Info Card -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Informasi Layanan</h3>
    </div>
    <div class="card-body">
        <p style="color: var(--gray-600)">Silakan klik pada makam di peta untuk melihat detail informasi. Untuk melakukan pemesanan dan konfirmasi pembayaran, silakan hubungi admin kami atau gunakan menu "Tambah / Pesan Makam".</p>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="card" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; min-width: 380px; max-width: 90vw; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); border: none; overflow: hidden;">
    <div class="card-header" style="background: linear-gradient(135deg, var(--primary), #2b8a3e); color: white; padding: 1.25rem;">
        <h3 class="card-title" style="color: white; margin: 0; font-size: 1.1rem;"><i data-lucide="info" style="width: 18px; margin-right: 8px;"></i> Info Makam: <span id="modalGraveId" style="font-weight: 800;"></span></h3>
        <button onclick="hideDetail()" style="border: none; background: rgba(255,255,255,0.2); border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: white"><i data-lucide="x" style="width: 16px;"></i></button>
    </div>
    <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
        <div id="maintAlert" style="display: none; background: #fffbeb; border: 1px solid #f59e0b; color: #b45309; padding: 10px; border-radius: 8px; font-size: 0.8rem; text-align: center; font-weight: 600;">
            <i data-lucide="alert-triangle" style="width: 16px; margin-right: 5px; display: inline-block; vertical-align: middle;"></i> SEDANG MAINTENANCE / PEMELIHARAAN
        </div>
        
        <div style="background: var(--gray-50); padding: 1rem; border-radius: 12px; border: 1px solid var(--gray-200);">
            <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 0.75rem;">
                <i data-lucide="user" style="color: var(--gray-500); width: 18px; margin-top: 2px;"></i>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Almarhum</div>
                    <div id="modalBuried" style="color: var(--primary); font-weight: 700; font-size: 1.1rem; margin-top: 2px;"></div>
                </div>
            </div>
            
            <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 0.75rem;">
                <i data-lucide="activity" style="color: var(--gray-500); width: 18px; margin-top: 2px;"></i>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Status</div>
                    <div style="margin-top: 4px;"><span id="modalStatus" class="badge"></span></div>
                </div>
            </div>
            
            <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 0.75rem;">
                <i data-lucide="book" style="color: var(--gray-500); width: 18px; margin-top: 2px;"></i>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Agama</div>
                    <div id="modalReligion" style="color: var(--gray-700); font-weight: 600; margin-top: 2px;"></div>
                </div>
            </div>

            <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 0.75rem;">
                <i data-lucide="users" style="color: var(--gray-500); width: 18px; margin-top: 2px;"></i>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Ahli Waris</div>
                    <div id="modalHeir" style="color: var(--gray-700); font-weight: 600; margin-top: 2px;"></div>
                </div>
            </div>

            <div style="display: flex; align-items: flex-start; gap: 10px;">
                <i data-lucide="calendar" style="color: var(--gray-500); width: 18px; margin-top: 2px;"></i>
                <div>
                    <div style="font-size: 0.75rem; color: var(--gray-500); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Tanggal Pemakaman</div>
                    <div id="modalDate" style="color: var(--gray-700); font-weight: 600; margin-top: 2px;"></div>
                </div>
            </div>
        </div>
    </div>
    <div style="padding: 1.25rem; background: var(--gray-50); border-top: 1px solid var(--gray-200); text-align: right;">
        <button class="btn btn-secondary" style="border-radius: 8px; font-weight: 600; padding: 0.5rem 1.5rem; transition: all 0.2s;" onclick="hideDetail()">Tutup Info</button>
    </div>
</div>
<div id="modalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;" onclick="hideDetail()"></div>

<script>
    window.addEventListener('DOMContentLoaded', (event) => {
        const urlParams = new URLSearchParams(window.location.search);
        const highlightId = urlParams.get('highlight');
        
        if (highlightId) {
            const target = document.getElementById('plot-' + highlightId.replace(' ', '-'));
            if (target) {
                target.classList.add('highlight-target');
                setTimeout(() => {
                    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // Optional: open detail automatically
                    target.click();
                }, 500);
            }
        }
    });
    function showDetail(id, buried, status, religion, heir, date, isMaint) {
        document.getElementById('modalGraveId').innerText = id;
        document.getElementById('modalBuried').innerText = buried || 'Makam Kosong / Tersedia';
        document.getElementById('modalStatus').innerText = status.toUpperCase();
        document.getElementById('modalStatus').className = 'badge badge-' + status;
        document.getElementById('modalReligion').innerText = religion;
        document.getElementById('modalHeir').innerText = heir || '-';
        document.getElementById('modalDate').innerText = date || '-';

        document.getElementById('maintAlert').style.display = isMaint ? 'block' : 'none';

        document.getElementById('detailModal').style.display = 'block';
        document.getElementById('modalOverlay').style.display = 'block';
        lucide.createIcons();
    }

    function hideDetail() {
        document.getElementById('detailModal').style.display = 'none';
        document.getElementById('modalOverlay').style.display = 'none';
    }

    function filterMap() {
        const religion = document.getElementById('religionFilter').value;
        const plots = document.querySelectorAll('.plot-cell');

        plots.forEach(plot => {
            if (religion === 'all') {
                plot.style.opacity = '1';
                plot.style.filter = 'none';
            } else if (plot.dataset.religion === religion) {
                plot.style.opacity = '1';
                plot.style.filter = 'none';
            } else {
                plot.style.opacity = '0.15';
                plot.style.filter = 'grayscale(1)';
            }
        });
    }
</script>
@endsection