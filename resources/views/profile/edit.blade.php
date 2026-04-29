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
                @php
                    $previewBg = '';
                    if (auth()->user()->photo) {
                        $previewBg = "url('/storage/" . auth()->user()->photo . "')";
                    } elseif (auth()->user()->avatar) {
                        $previewBg = "url('" . auth()->user()->avatar . "')";
                    }
                @endphp
                <div id="profilePreview" style="width: 120px; height: 120px; border-radius: 50%; background: #e2e8f0 {{ $previewBg ? $previewBg : '' }}; background-size: cover; background-position: center; margin: 0 auto 1rem; border: 4px solid var(--primary); box-shadow: var(--shadow);">
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<!-- Modal Cropper -->
<div id="cropperModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; padding: 24px; border-radius: 16px; width: 90%; max-width: 450px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; margin-bottom: 16px; color: var(--gray-800); font-size: 1.25rem;">Sesuaikan Posisi Foto</h3>
        <div style="width: 100%; height: 300px; background: #f8fafc; margin-bottom: 20px; border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
            <img id="cropperImage" style="max-width: 100%; max-height: 100%; display: block;">
        </div>
        <div style="display: flex; justify-content: flex-end; gap: 12px;">
            <button type="button" class="btn btn-outline" style="padding: 0.5rem 1rem;" onclick="closeCropper(true)">Batal</button>
            <button type="button" class="btn btn-primary" style="padding: 0.5rem 1.25rem;" onclick="applyCrop()">
                <i data-lucide="check" style="width: 18px; margin-right: 6px;"></i> Terapkan
            </button>
        </div>
    </div>
</div>

<style>
    /* Circular Crop Box */
    .cropper-view-box, .cropper-face {
        border-radius: 50%;
    }
    .cropper-view-box {
        outline: 2px solid var(--primary);
        outline-color: rgba(0, 0, 0, 0.75);
    }
</style>

<script>
    let cropper;
    
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('cropperImage').src = e.target.result;
            document.getElementById('cropperModal').style.display = 'flex';
            
            if (cropper) {
                cropper.destroy();
            }
            
            const image = document.getElementById('cropperImage');
            cropper = new Cropper(image, {
                aspectRatio: 1, // Perfect square/circle
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 0.8,
                restore: false,
                guides: false,
                center: false,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
            
            // Re-render lucide icons in modal if needed
            if(window.lucide) lucide.createIcons();
        };
        reader.readAsDataURL(file);
    }
    
    function closeCropper(isCancel = false) {
        document.getElementById('cropperModal').style.display = 'none';
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        if (isCancel) {
            // Only clear input if user cancelled, so they can re-select the same file
            document.querySelector('input[name="photo"]').value = '';
        }
    }
    
    function applyCrop() {
        if (!cropper) return;
        
        cropper.getCroppedCanvas({
            width: 400,
            height: 400,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        }).toBlob(function(blob) {
            // Update UI preview
            const url = URL.createObjectURL(blob);
            document.getElementById('profilePreview').style.backgroundImage = 'url(' + url + ')';
            
            // Set cropped blob to the file input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(new File([blob], 'cropped_profile.jpg', { type: 'image/jpeg' }));
            document.querySelector('input[name="photo"]').files = dataTransfer.files;
            
            closeCropper(false);
        }, 'image/jpeg', 0.9);
    }
</script>
@endsection
