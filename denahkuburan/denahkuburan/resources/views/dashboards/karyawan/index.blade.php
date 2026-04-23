@extends('layouts.app', ['title' => 'Dashboard Operasional Karyawan'])

@section('content')
<style>
    :root {
        --map-grass: #82c91e;
        --map-grass-dark: #5c940d;
        --map-road: #495057;
        --map-tree: #2f9e44;
    }

    /* Map Layout */
    .cemetery-map-container {
        background: var(--map-grass);
        border: 4px solid var(--map-grass-dark);
        border-radius: 20px;
        padding: 40px 20px;
        position: relative;
        overflow: hidden;
        box-shadow: inset 0 0 100px rgba(0,0,0,0.1);
        min-height: 800px;
    }

    .map-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        background: rgba(255,255,255,0.9);
        padding: 1rem 1.5rem;
        border-radius: 12px;
        backdrop-filter: blur(5px);
    }

    /* Blocks & Plots */
    .blocks-row {
        display: flex;
        justify-content: space-around;
        gap: 20px;
        margin-bottom: 60px;
        z-index: 10;
        position: relative;
    }

    .block-unit {
        background: rgba(255, 255, 255, 0.2);
        padding: 10px;
        border-radius: 10px;
        text-align: center;
    }

    .block-name-label {
        background: #2b8a3e;
        color: white;
        padding: 2px 12px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 0.8rem;
        display: inline-block;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .plot-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 5px;
    }

    /* Plot Styles */
    .plot {
        width: 45px;
        height: 45px;
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

    .plot:hover {
        transform: scale(1.1);
        z-index: 20;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .plot-religion { opacity: 0.7; font-size: 0.5rem; }
    .plot-number { line-height: 1; }
    .plot-sub { font-size: 0.5rem; margin-top: 1px; }

    /* Religion Colors */
    .rel-islam { background: #fee101; border-color: #fcc419; color: #826a00; } /* Yellow */
    .rel-protestan { background: #4dabf7; border-color: #339af0; color: #004b82; } /* Blue */
    .rel-katolik { background: #fab005; border-color: #f08c00; color: #824b00; } /* Orange/Dark Yellow */
    .rel-hindu { background: #be4bdb; border-color: #ae3ec9; color: #4b0082; } /* Purple */
    .rel-budha { background: #ff8787; border-color: #fa5252; color: #820000; } /* Pink/Red */
    .rel-konghucu { background: #74c0fc; border-color: #4dabf7; color: #003e82; } /* Light Blue */
    .rel-umum { background: #adb5bd; border-color: #868e96; color: #343a40; } /* Gray */

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

    /* Status Overlays */
    .status-booked { border-style: dashed !important; border-width: 2px; }
    .status-occupied { background: #212529 !important; border-color: #000; color: #fff !important; }
    .status-occupied::after {
        content: '🪦';
        font-size: 1.2rem;
        position: absolute;
    }
    .status-occupied .plot-number, .status-occupied .plot-religion, .status-occupied .plot-sub {
        opacity: 0;
    }

    /* Road & Decorations */
    .road-strip {
        height: 60px;
        background: var(--map-road);
        position: relative;
        margin: 40px -20px;
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

    .tree {
        position: absolute;
        width: 40px;
        height: 40px;
        background: radial-gradient(circle at 30% 30%, #40c057, #2b8a3e);
        border-radius: 50%;
        box-shadow: 0 8px 15px rgba(0,0,0,0.3);
        z-index: 15;
    }

    .maint-ribbon {
        position: absolute;
        top: -10px;
        left: -10px;
        background: #f59e0b;
        color: white;
        font-size: 0.5rem;
        padding: 1px 5px;
        border-radius: 4px;
        z-index: 21;
        font-weight: 800;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    /* Legend */
    .map-legend {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: rgba(255,255,255,0.9);
        padding: 10px;
        border-radius: 10px;
        font-size: 0.7rem;
        display: flex;
        gap: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .legend-item { display: flex; align-items: center; gap: 5px; }
    .legend-box { width: 12px; height: 12px; border-radius: 2px; }
</style>

<div class="map-header">
    <h2 style="margin: 0; font-weight: 800; color: var(--primary);">Denah Makam Digital</h2>
    <div style="display: flex; gap: 1rem; align-items: center;">
        <select class="form-control" style="width: auto;" id="religionFilter" onchange="filterMap()">
            <option value="all">-- Semua Agama --</option>
            <option value="islam">Islam</option>
            <option value="protestan">Protestan</option>
            <option value="katolik">Katolik</option>
            <option value="hindu">Hindu</option>
            <option value="budha">Budha</option>
            <option value="konghucu">Konghucu</option>
            <option value="umum">Umum</option>
        </select>
        <div style="background: linear-gradient(135deg, #2b8a3e, #40c057); color: white; padding: 8px 18px; border-radius: 30px; font-size: 0.85rem; font-weight: 600; box-shadow: 0 4px 10px rgba(43, 138, 62, 0.3); display: flex; align-items: center; gap: 8px;">
            <i data-lucide="mouse-pointer-click" style="width: 16px;"></i> Klik makam untuk detail
        </div>
    </div>
</div>

<div class="cemetery-map-container">
    @php
        $blocks = $graves->groupBy('block_name');
        $blockKeys = $blocks->keys()->sort()->values();
        $rows = $blockKeys->chunk(4);
    @endphp

    @foreach($rows as $blockRow)
    <div class="blocks-row" style="margin-bottom: 20px;">
        @foreach($blockRow as $blockName)
        <div class="block-unit">
            <div class="block-name-label">{{ $blockName }}</div>
            <div class="plot-grid">
                @foreach($blocks[$blockName]->take(20) as $grave)
                    @include('dashboards.karyawan._plot', ['grave' => $grave])
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
    
    @if(!$loop->last)
    <!-- Road separator -->
    <div class="road-strip">
        <div class="tree" style="left: 15%; top: -20px;"></div>
        <div class="tree" style="left: 45%; top: 30px;"></div>
        <div class="tree" style="left: 75%; top: -10px;"></div>
    </div>
    @endif
    @endforeach

    <div class="map-legend">
        <div class="legend-item"><div class="legend-box rel-islam"></div> Islam</div>
        <div class="legend-item"><div class="legend-box rel-protestan"></div> Protestan</div>
        <div class="legend-item"><div class="legend-box rel-katolik"></div> Katolik</div>
        <div class="legend-item"><div class="legend-box" style="background: #212529; border: 1px solid #000;"></div> Terisi</div>
        <div class="legend-item"><div class="legend-box status-booked" style="border: 2px dashed #000;"></div> Dipesan</div>
    </div>
</div>

<!-- Modal -->
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
    function showDetail(id, buried, status, religion, isMaint, heir, date) {
        document.getElementById('modalGraveId').innerText = id;
        document.getElementById('modalBuried').innerText = buried || 'Makam Kosong / Tersedia';
        document.getElementById('modalStatus').innerText = status.toUpperCase();
        document.getElementById('modalStatus').className = 'badge badge-' + status;
        document.getElementById('modalReligion').innerText = religion.toUpperCase();
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
        const plots = document.querySelectorAll('.plot');
        
        plots.forEach(plot => {
            if (religion === 'all') {
                plot.style.opacity = '1';
                plot.style.filter = 'none';
            } else if (plot.dataset.religion === religion) {
                plot.style.opacity = '1';
                plot.style.filter = 'none';
            } else {
                plot.style.opacity = '0.2';
                plot.style.filter = 'grayscale(1)';
            }
        });
    }
</script>
@endsection
