@extends('layouts.app', ['title' => 'Dashboard Super Admin'])

@section('content')
<div class="card-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem">
    <div class="card" style="border-bottom: 4px solid var(--primary)">
        <div style="display: flex; justify-content: space-between; align-items: center">
            <div>
                <p style="color: var(--gray-600); font-size: 0.875rem">Total Pengguna</p>
                <h2 style="font-size: 2rem; margin-top: 0.5rem">{{ $total_users }}</h2>
            </div>
            <i data-lucide="users" style="width: 40px; height: 40px; color: var(--primary); opacity: 0.2"></i>
        </div>
    </div>
    
    <div class="card" style="border-bottom: 4px solid var(--success)">
        <div style="display: flex; justify-content: space-between; align-items: center">
            <div>
                <p style="color: var(--gray-600); font-size: 0.875rem">Total Makam</p>
                <h2 style="font-size: 2rem; margin-top: 0.5rem">{{ $total_graves }}</h2>
            </div>
            <i data-lucide="map" style="width: 40px; height: 40px; color: var(--success); opacity: 0.2"></i>
        </div>
    </div>
</div>

    <style>
        .map-container {
            position: relative;
            background: #84cc16;
            /* Grass green */
            border-radius: var(--radius-lg);
            padding: 2rem;
            box-shadow: inset 0 0 50px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 4px solid #4d7c0f;
        }

        @keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); transform: scale(1); }
            50% { transform: scale(1.1); }
            70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); transform: scale(1); }
        }

        .my-grave-indicator {
            position: relative;
            z-index: 10 !important;
            border: 2px solid #16a34a !important;
            animation: pulse-green 1.5s infinite;
        }
        
        .my-grave-indicator::after {
            content: '';
            position: absolute;
            top: -5px;
            right: -5px;
            width: 12px;
            height: 12px;
            background: #22c55e;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        /* Marker Merah untuk target pencarian */
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); transform: scale(1); }
            50% { transform: scale(1.2); }
            70% { box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); transform: scale(1); }
        }

        .target-highlight {
            position: relative;
            z-index: 20 !important;
            border: 3px solid #ef4444 !important;
            animation: pulse-red 1.5s infinite;
            background: #fef2f2 !important;
        }

        .target-highlight::after {
            content: '📍';
            position: absolute;
            top: -15px;
            font-size: 1.2rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }

        /* Decoration: Trees / Greenery */
        .tree {
            position: absolute;
            background: #22c55e;
            border-radius: 50%;
            box-shadow: inset -5px -5px 15px rgba(0, 0, 0, 0.6), 2px 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 7;
            opacity: 0.9;
        }

        /* Varian ukuran pohon untuk terlihat natural */
        .tree-sm { width: 30px; height: 30px; }
        .tree-md { width: 40px; height: 40px; }
        .tree-lg { width: 50px; height: 50px; background: #16a34a; }

        .map-layout {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .road-horizontal {
            height: 40px;
            background: #64748b;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        .road-horizontal::after {
            content: '';
            width: 100%;
            height: 2px;
            background: repeating-linear-gradient(90deg, #fff, #fff 10px, transparent 10px, transparent 20px);
            position: absolute;
        }

        .blocks-container {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .block-zone-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .block-zone-title {
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
            background: rgba(0, 0, 0, 0.4);
            padding: 2px 8px;
            border-radius: 4px;
        }

        .block-zone {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
            gap: 4px;
            padding: 8px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            width: 100%;
            max-width: 250px;
        }

        /* Colors and Types */
        /* Religion Colors */
        .rel-islam { background: #22c55e; border-color: #16a34a; color: #f0fdf4; }
        .rel-protestan { background: #3b82f6; border-color: #2563eb; color: #eff6ff; }
        .rel-katolik { background: #eab308; border-color: #ca8a04; color: #fefce8; }
        .rel-hindu { background: #a855f7; border-color: #9333ea; color: #faf5ff; }
        .rel-budha { background: #ef4444; border-color: #dc2626; color: #fef2f2; }
        .rel-konghucu { background: #06b6d4; border-color: #0891b2; color: #ecfeff; }
        .rel-umum { background: #64748b; border-color: #475569; color: #f8fafc; }

        /* Purple */

        /* Specific Status Overrides */
        .status-occupied {
            position: relative;
            opacity: 0.95;
        }
        .status-occupied::before {
            content: ''; /* Logo makam hitam murni (pure CSS) */
            position: absolute;
            width: 22px;
            height: 28px;
            background-color: #111827; /* Solid black/dark */
            border-radius: 10px 10px 2px 2px;
            opacity: 0.8;
            z-index: 0;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .status-occupied > span, .status-occupied > div {
            z-index: 1; /* keep text above the mark */
            position: relative;
            color: #ffffff !important;
            text-shadow: 1px 1px 2px #000000, -1px -1px 2px #000000, 0 0 5px #000000;
        }
        
        .status-booked {
            position: relative;
            border-style: dashed !important;
        }

        .plot-box {
            width: 40px;
            height: 40px;
            border: 2px solid;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: bold;
            text-align: center;
            transition: all 0.2s;
            cursor: pointer;
            padding: 2px;
        }

        .plot-box:hover {
            transform: scale(1.1);
            z-index: 5;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        /* We'll use a small dot or letter for status inside the small box */
        .plot-status-mini {
            font-size: 0.5rem;
            margin-top: 2px;
            background: rgba(255, 255, 255, 0.8);
            padding: 1px 3px;
            border-radius: 2px;
            color: #000;
            line-height: 1;
        }

        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
            background: #fff;
            padding: 1rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }
    </style>

    <div class="card mb-4" style="margin-bottom: 2rem;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <h3 class="card-title mb-0" style="margin: 0;">Denah Makam Keseluruhan</h3>
            
            <!-- Top Right Filter & Indicator -->
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <form method="GET" action="" style="margin: 0; display: flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.9); padding: 5px 15px; border-radius: 8px; border: 1px solid var(--gray-200);">
                    <label style="font-weight: 600; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 5px;">
                        <i data-lucide="filter" style="width: 16px;"></i> Filter:
                    </label>
                    <select name="religion" class="form-control" style="width: 150px; padding: 4px 8px;" onchange="this.form.submit()">
                        <option value="">-- Semua --</option>
                        <option value="islam" {{ request('religion') == 'islam' ? 'selected' : '' }}>Islam</option>
                        <option value="protestan" {{ request('religion') == 'protestan' ? 'selected' : '' }}>Protestan</option>
                        <option value="katolik" {{ request('religion') == 'katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="hindu" {{ request('religion') == 'hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="budha" {{ request('religion') == 'budha' ? 'selected' : '' }}>Budha</option>
                        <option value="konghucu" {{ request('religion') == 'konghucu' ? 'selected' : '' }}>Konghucu</option>
                        <option value="umum" {{ request('religion') == 'umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </form>

                <span style="background: var(--primary); color: white; padding: 6px 16px; border-radius: 999px; box-shadow: var(--shadow-sm); font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="map-pin" style="width: 16px;"></i> Agama: {{ request('religion') ? ucfirst(request('religion')) : 'Semua' }}
                </span>
            </div>
        </div>
        <div class="card-body">
            
            <div class="map-container">
                @php
                    $allGraves = \App\Models\Grave::all();
                    $blocksGrouped = $allGraves->groupBy('block_name')->values(); 
                    $filterReligion = request('religion');
                @endphp
                
                <div class="map-layout">
                    @foreach($blocksGrouped->chunk(4) as $rowIdx => $blockRow)
                        <div class="blocks-container">
                            @foreach($blockRow as $blockIdx => $gravesInBlock)
                                @php
                                    $firstGrave = $gravesInBlock->first();
                                    $religion = $firstGrave ? ($firstGrave->religion ?? 'umum') : 'umum';
                                    $colorClass = 'rel-' . strtolower($religion);
                                    $blockName = $firstGrave ? $firstGrave->block_name : 'Blok Baru';
                                @endphp
                                <div class="block-zone-wrapper">
                                    <div class="block-zone-title">{{ $blockName }}</div>
                                    <div class="block-zone">
                                        @foreach($gravesInBlock as $grave)
                                        @php
                                            $numParts = explode('-', $grave->grave_number);
                                            $displayNum = count($numParts) > 1 ? $numParts[1] : $grave->grave_number;
                                            
                                            // Check if it matches the current filter
                                            $isVisible = empty($filterReligion) || $grave->religion == $filterReligion;
                                        @endphp
                                        
                                        @if($isVisible)
                                            @php
                                                $isMyGrave = false;
                                                $isTarget = request('target_grave') == $grave->id;
                                                $statusClass = $grave->status == 'occupied' ? 'status-occupied' : ($grave->status == 'booked' ? 'status-booked' : '');
                                            @endphp
                                            <div class="plot-box {{ $colorClass }} {{ $statusClass }} {{ $isMyGrave ? 'my-grave-indicator' : '' }} {{ $isTarget ? 'target-highlight' : '' }}"
                                                id="grave_{{ $grave->id }}"
                                                title="Makam No: {{ $grave->grave_number }}&#10;Agama: {{ ucfirst($grave->religion) }}&#10;Status: {{ $grave->status }}&#10;Blok Asli: {{ $grave->block_name }}">
                                                <span style="font-size: 0.45rem; font-weight: normal; margin-bottom: -2px; color: inherit; opacity: 0.8">{{ strtoupper(substr($grave->religion ?? '', 0, 3)) }}</span>
                                                <span>{{ $displayNum }}</span>
                                                <div class="plot-status-mini" style="color: #000;">{{ substr(strtoupper($grave->status), 0, 1) }}</div>
                                            </div>
                                        @else
                                            {{-- Invisible placeholder to reserve spatial grid slots --}}
                                            <div style="width: 40px; height: 40px; visibility: hidden; padding: 2px; border: 2px solid transparent;"></div>
                                        @endif
                                        
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if(!$loop->last)
                            <!-- Center Road -->
                            <div class="road-horizontal" style="margin: 1rem 0;">
                                <!-- Tepi atas jalan -->
                                <div class="tree tree-md" style="top: -20px; left: 10%;"></div>
                                <div class="tree tree-sm" style="top: -15px; left: 35%;"></div>
                                <div class="tree tree-lg" style="top: -25px; right: 20%;"></div>
                                
                                <!-- Tepi bawah jalan -->
                                <div class="tree tree-lg" style="bottom: -25px; left: 25%;"></div>
                                <div class="tree tree-md" style="bottom: -20px; right: 40%;"></div>
                                <div class="tree tree-sm" style="bottom: -15px; right: 5%;"></div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
        </div>
    </div>
    <script>
        window.addEventListener('load', () => {
            const targetId = "{{ request('target_grave') }}";
            if(targetId) {
                const element = document.getElementById('grave_' + targetId);
                if(element) {
                    setTimeout(() => {
                        element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 500);
                }
            }
        });
    </script>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Aktivitas Sistem</h3>
    </div>
    <div style="padding: 1rem; text-align: center; color: var(--gray-500)">
        <p>Belum ada log sistem terbaru hari ini.</p>
    </div>
</div>
@endsection
