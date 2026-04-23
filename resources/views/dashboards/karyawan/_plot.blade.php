@php
    $isMaint = in_array($grave->block_name, $maintenance_blocks ?? []);
    $religion = $grave->religion ?? 'umum';
    $relClass = 'rel-' . strtolower($religion);
    $statusClass = 'status-' . strtolower($grave->status);
    $relShort = strtoupper(substr($religion, 0, 3));
@endphp

<div class="plot {{ $relClass }} {{ $statusClass }} {{ $isMaint ? 'in-maintenance' : '' }}" 
     data-religion="{{ strtolower($religion) }}"
     onclick="showDetail('{{ $grave->grave_number }}', '{{ addslashes($grave->buried_name ?? '') }}', '{{ $grave->status }}', '{{ $religion }}', {{ $isMaint ? 'true' : 'false' }}, '{{ addslashes($grave->heir_name ?? '') }}', '{{ $grave->burial_date ?? '' }}')">
    
    @if($isMaint)
        <div class="maint-ribbon">MAINT</div>
    @endif

    <span class="plot-religion">{{ $relShort }}</span>
    <span class="plot-number">{{ str_replace($grave->block_name . '-', '', $grave->grave_number) }}</span>
    <div class="plot-sub">{{ substr($grave->block_name, -1) }}</div>
</div>
