@extends('layouts.app', ['title' => 'Pengaturan Profil'])

@section('content')
<style>
    .btn-wood {
        background: linear-gradient(135deg, #d4a373 25%, #cc9a66 25%, #cc9a66 50%, #d4a373 50%, #d4a373 75%, #cc9a66 75%, #cc9a66 100%);
        background-size: 20px 20px;
        color: #fff;
        border: none;
        font-weight: 600;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .btn-wood:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(0,0,0,0.15);
    }
</style>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h3 class="card-title">Perbarui Profil Anda</h3>
    </div>
    
    <div class="card-body">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div style="text-align: center; margin-bottom: 2rem;">
                <div id="profilePreview" style="width: 120px; height: 120px; border-radius: 50%; background: #e2e8f0 {{ auth()->user()->photo ? "url('/storage/" . auth()->user()->photo . "')" : '' }}; background-size: cover; background-position: center; margin: 0 auto 1rem; border: 4px solid var(--primary); box-shadow: var(--shadow);">
                </div>
                <!-- Show name similar to standard welcome -->
                <h4 style="margin: 0.5rem 0 1rem 0; color: var(--gray-800); font-weight: 600;">{{ auth()->user()->name }}</h4>
                
                <div>
                    <label class="btn btn-wood" style="cursor: pointer; padding: 0.5rem 1rem; display: inline-block; border-radius: 999px;">
                        <i data-lucide="image" style="width: 16px; margin-right: 4px; display: inline-block; vertical-align: middle;"></i><span>Ubah Foto</span>
                        <input type="file" name="photo" style="display: none;" accept="image/*" onchange="previewImage(event)">
                    </label>
                    <p style="font-size: 0.75rem; color: var(--gray-500); margin-top: 0.5rem;">Saran: Gambar persegi, format JPG/PNG</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap (Customer)</label>
                <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('profilePreview');
            output.style.backgroundImage = 'url(' + reader.result + ')';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
