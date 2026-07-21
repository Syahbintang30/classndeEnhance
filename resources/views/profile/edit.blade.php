@extends('layouts.app')

@section('title', 'Edit Profile - Guitarclassbynde')

@push('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            important: true,
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        display: ['"Bebas Neue"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #08080a !important; color: #ffffff !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .font-display { font-family: 'Bebas Neue', cursive !important; letter-spacing: 1px; }
        body > nav, .global-nav { display: none !important; }
        .glass-panel {
            background: rgba(12, 12, 18, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        #cropper-modal {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 99999;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(12px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        #cropper-area {
            position: relative;
            width: 100%;
            max-width: 480px;
            height: 340px;
            overflow: hidden;
            background: #000;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.1);
            user-select: none;
            touch-action: none;
        }
        #cropper-image {
            position: absolute;
            top: 0; left: 0;
            max-width: none;
            cursor: move;
            transform-origin: center center;
        }
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-[#08080a] text-white flex flex-col relative selection:bg-blue-600 selection:text-white overflow-hidden pb-16">
    
    {{-- Ambient Mesh Background Glows --}}
    <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-blue-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-purple-600/15 rounded-full blur-[150px] pointer-events-none z-0 mix-blend-screen"></div>

    {{-- LMS Floating Glass Pill Header --}}
    <div class="relative z-20">
        @include('layouts.lms_header')
    </div>

    {{-- Main Container --}}
    <main class="flex-1 w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10 space-y-8">
        
        <!-- Header Page Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-bold uppercase tracking-widest mb-2">
                    Account Settings
                </div>
                <h1 class="font-display text-4xl sm:text-5xl text-white tracking-wide uppercase leading-none">Edit <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Profile</span></h1>
                <p class="text-gray-400 text-xs sm:text-sm mt-1">Manage your personal information, profile photo, and security settings.</p>
            </div>
            <a href="{{ route('profile') }}" class="py-2.5 px-5 rounded-xl bg-white/5 border border-white/10 hover:border-white/20 text-xs font-bold text-gray-300 hover:text-white transition inline-flex items-center gap-2 self-start sm:self-auto">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                <span>View Profile</span>
            </a>
        </div>

        <!-- Flash Messages -->
        @if(session('status') || session('success') || session('error') || $errors->any())
        <div class="space-y-3">
            @if(session('status') === 'profile-updated' || session('success'))
                <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                    <span>{{ session('success') ?? 'Profile updated successfully.' }}</span>
                </div>
            @endif
            @if(session('status') === 'password-updated')
                <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 text-xs flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                    <span>Password updated successfully.</span>
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs space-y-1">
                    <div class="font-bold flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
                        <span>Please fix the following issues:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 pl-4 text-gray-300">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        @endif

        <!-- Card 1: Profile Information Section -->
        <div class="bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-6 sm:p-8 shadow-2xl relative overflow-hidden space-y-6">
            
            <!-- Glowing top accent line -->
            <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>

            <div class="border-b border-white/10 pb-4">
                <h2 class="font-display text-2xl sm:text-3xl text-white tracking-wide uppercase">Profile Information</h2>
                <p class="text-gray-400 text-xs mt-1">Update your account name, email address, and avatar image.</p>
            </div>

            <!-- Avatar Uploader Row -->
            <div class="flex items-center gap-5 p-4 rounded-2xl bg-white/5 border border-white/10">
                @php $avatar = $user->photoUrl(); @endphp
                <div class="relative shrink-0">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 border-2 border-white/20 flex items-center justify-center font-bold text-2xl text-white shadow-xl overflow-hidden">
                        @if($avatar)
                            <img id="photo-preview" src="{{ $avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <span class="fallback-avatar hidden w-full h-full items-center justify-center bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-bold text-2xl">{{ mb_substr($user->name ?? 'U', 0, 1) }}</span>
                        @else
                            <span id="photo-preview" class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-bold text-2xl">{{ mb_substr($user->name ?? 'U', 0, 1) }}</span>
                        @endif
                    </div>
                </div>

                <div class="space-y-1.5">
                    <button id="change-photo" type="button" class="px-4 py-2 rounded-xl bg-zinc-900 border border-white/10 hover:border-blue-500/40 text-xs font-bold text-white transition flex items-center gap-2 cursor-pointer shadow-md">
                        <i class="fa-solid fa-camera text-blue-400 text-xs"></i>
                        <span>Change Photo</span>
                    </button>
                    <p class="text-[11px] text-gray-400">Supported formats: JPG, PNG (Max 2MB)</p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('patch')
                <input id="photo" name="photo" type="file" accept="image/*" class="hidden">

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-300" for="name">Full Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required maxlength="255" class="w-full px-4 py-3 rounded-xl bg-zinc-900/80 border border-white/10 text-white placeholder-gray-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    @if($errors->has('name'))
                        <p class="text-xs text-rose-400 font-medium">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-300" for="email">Email Address</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required maxlength="255" class="w-full px-4 py-3 rounded-xl bg-zinc-900/80 border border-white/10 text-white placeholder-gray-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    @if($errors->has('email'))
                        <p class="text-xs text-rose-400 font-medium">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs tracking-wider uppercase shadow-lg shadow-blue-600/30 transition hover:scale-105 cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Save Changes</span>
                    </button>
                    <a href="{{ route('profile') }}" class="px-5 py-3 rounded-xl bg-white/5 border border-white/10 hover:border-white/20 text-xs font-bold text-gray-300 hover:text-white transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Card 2: Password Security Section -->
        <div class="bg-zinc-950/60 border border-white/10 backdrop-blur-3xl rounded-[2rem] p-6 sm:p-8 shadow-2xl relative overflow-hidden space-y-6" id="password">
            
            <!-- Glowing top accent line -->
            <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>

            <div class="border-b border-white/10 pb-4">
                <h2 class="font-display text-2xl sm:text-3xl text-white tracking-wide uppercase">Change Password</h2>
                <p class="text-gray-400 text-xs mt-1">Ensure your account is using a long and random password to stay secure.</p>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                @csrf
                @method('put')

                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-300" for="current_password">Current Password</label>
                    <div class="relative">
                        <input id="current_password" name="current_password" type="password" required autocomplete="current-password" placeholder="••••••••" class="w-full px-4 py-3 pr-12 rounded-xl bg-zinc-900/80 border border-white/10 text-white placeholder-gray-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                        <button type="button" class="ep-pw-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white p-1" data-target="current_password">
                            <i class="fa-solid fa-eye text-sm"></i>
                        </button>
                    </div>
                    @if($errors->has('current_password'))
                        <p class="text-xs text-rose-400 font-medium">{{ $errors->first('current_password') }}</p>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-300" for="password_input">New Password</label>
                        <div class="relative">
                            <input id="password_input" name="password" type="password" required autocomplete="new-password" placeholder="Minimum 8 characters" class="w-full px-4 py-3 pr-12 rounded-xl bg-zinc-900/80 border border-white/10 text-white placeholder-gray-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                            <button type="button" class="ep-pw-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white p-1" data-target="password_input">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                        @if($errors->has('password'))
                            <p class="text-xs text-rose-400 font-medium">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-gray-300" for="password_confirmation">Confirm New Password</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Repeat your new password" class="w-full px-4 py-3 pr-12 rounded-xl bg-zinc-900/80 border border-white/10 text-white placeholder-gray-500 text-sm focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                            <button type="button" class="ep-pw-toggle absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white p-1" data-target="password_confirmation">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </button>
                        </div>
                        @if($errors->has('password_confirmation'))
                            <p class="text-xs text-rose-400 font-medium">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs tracking-wider uppercase shadow-lg shadow-blue-600/30 transition hover:scale-105 cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-key"></i>
                        <span>Save Password</span>
                    </button>
                    <a href="{{ route('profile') }}" class="px-5 py-3 rounded-xl bg-white/5 border border-white/10 hover:border-white/20 text-xs font-bold text-gray-300 hover:text-white transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </main>

</div>

<!-- Avatar Cropper Modal -->
<div id="cropper-modal">
    <div id="cropper-dialog" class="w-full max-w-xl bg-zinc-950 border border-white/10 rounded-3xl p-6 shadow-2xl space-y-4">
        <div class="flex justify-between items-center pb-2 border-b border-white/10">
            <h3 class="font-bold text-white text-base">Adjust Photo</h3>
            <div class="flex gap-2">
                <button id="crop-remove" type="button" class="px-3 py-1.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 hover:bg-rose-500/20 text-xs font-bold transition">Remove</button>
                <button id="crop-cancel" type="button" class="px-3 py-1.5 rounded-xl bg-white/5 border border-white/10 text-gray-300 hover:text-white text-xs font-bold transition">Cancel</button>
                <button id="crop-apply" type="button" class="px-4 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md transition">Apply</button>
            </div>
        </div>
        <div class="flex flex-col items-center gap-4">
            <div id="cropper-area">
                <img id="crop-image" src="" alt="to crop">
                <div class="absolute inset-0 border-2 border-dashed border-white/30 shadow-[0_0_0_9999px_rgba(0,0,0,0.5)] pointer-events-none rounded-xl"></div>
            </div>
            <div class="flex items-center gap-3 w-full max-w-xs justify-center text-xs text-gray-300">
                <span class="font-bold">Zoom</span>
                <input id="crop-zoom" type="range" min="0.5" max="3" step="0.01" value="1" class="w-full accent-blue-500 cursor-pointer">
            </div>
        </div>
    </div>
</div>

<script>
// Scroll to password section if hash
(function(){
    if (window.location.hash === '#password') {
        var el = document.getElementById('password');
        if (el) setTimeout(function(){ el.scrollIntoView({behavior:'smooth',block:'start'}); var inp = el.querySelector('input[name="current_password"]'); if(inp) inp.focus(); }, 80);
    }
})();

// Password toggles
document.querySelectorAll('.ep-pw-toggle').forEach(function(btn){
    btn.addEventListener('click', function(){
        var input = document.getElementById(btn.getAttribute('data-target'));
        if(!input) return;
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            if(icon) { icon.classList.remove('fa-eye'); icon.classList.add('fa-eye-slash'); }
        } else {
            input.type = 'password';
            if(icon) { icon.classList.remove('fa-eye-slash'); icon.classList.add('fa-eye'); }
        }
    });
});

// Cropper logic
(function(){
    var changeBtn = document.getElementById('change-photo');
    var nativeInput = document.getElementById('photo');
    var preview = document.getElementById('photo-preview');
    var modal = document.getElementById('cropper-modal');
    var cropImage = document.getElementById('crop-image');
    var cropArea = document.getElementById('cropper-area');
    var zoom = document.getElementById('crop-zoom');
    var apply = document.getElementById('crop-apply');
    var cancel = document.getElementById('crop-cancel');
    var rem = document.getElementById('crop-remove');
    var state = { x:0, y:0, scale:1, isDown:false, startX:0, startY:0 };

    function ensurePreviewImage(){
        if(preview && preview.tagName === 'IMG') return preview;
        var img = document.createElement('img');
        img.id = 'photo-preview';
        img.className = 'w-full h-full object-cover';
        img.alt = '';
        if(preview && preview.parentNode) preview.parentNode.replaceChild(img, preview);
        preview = img;
        return preview;
    }

    function showModal(){ modal.style.display='flex'; document.body.style.overflow='hidden'; }
    function hideModal(){ modal.style.display='none'; document.body.style.overflow=''; }

    if (changeBtn) changeBtn.addEventListener('click', function(){ nativeInput.click(); });

    if (nativeInput) {
        nativeInput.addEventListener('change', function(){
            var f = this.files && this.files[0]; if(!f) return;
            var reader = new FileReader();
            reader.onload = function(e){
                cropImage.src = e.target.result;
                cropImage.onload = function(){ state.scale=1; state.x=0; state.y=0; updateTransform(); zoom.value=1; showModal(); };
            };
            reader.readAsDataURL(f);
        });
    }

    if (cropImage) {
        cropImage.addEventListener('pointerdown', function(e){ state.isDown=true; state.startX=e.clientX; state.startY=e.clientY; cropImage.setPointerCapture(e.pointerId); });
        window.addEventListener('pointermove', function(e){ if(!state.isDown) return; state.x += e.clientX-state.startX; state.y += e.clientY-state.startY; state.startX=e.clientX; state.startY=e.clientY; updateTransform(); });
        window.addEventListener('pointerup', function(){ state.isDown=false; });
    }

    if (zoom) zoom.addEventListener('input', function(){ state.scale=parseFloat(this.value); updateTransform(); });
    function updateTransform(){ if(cropImage) cropImage.style.transform='translate('+state.x+'px,'+state.y+'px) scale('+state.scale+')'; }

    if (apply) {
        apply.addEventListener('click', function(){
            var outSize = Math.min(800, Math.round(cropArea.clientWidth * (window.devicePixelRatio||1)));
            var canvas = document.createElement('canvas'); canvas.width=outSize; canvas.height=outSize;
            var ctx = canvas.getContext('2d');
            var areaW=cropArea.clientWidth; var areaH=cropArea.clientHeight;
            var iRW=cropImage.naturalWidth*state.scale; var iRH=cropImage.naturalHeight*state.scale;
            var offX=(areaW/2)-(iRW/2)+state.x; var offY=(areaH/2)-(iRH/2)+state.y;
            var srcX=Math.max(0,Math.round((-offX)/state.scale)); var srcY=Math.max(0,Math.round((-offY)/state.scale));
            var srcW=Math.min(cropImage.naturalWidth-srcX,Math.round(areaW/state.scale));
            var srcH=Math.min(cropImage.naturalHeight-srcY,Math.round(areaH/state.scale));
            try{ ctx.fillStyle='#111'; ctx.fillRect(0,0,outSize,outSize); ctx.drawImage(cropImage,srcX,srcY,srcW,srcH,0,0,outSize,outSize); }catch(e){ alert('Failed to crop image'); hideModal(); return; }
            canvas.toBlob(function(blob){
                if(!blob){ alert('Failed to generate image'); hideModal(); return; }
                var file = new File([blob],'profile.jpg',{type:'image/jpeg'});
                var dt = new DataTransfer(); dt.items.add(file); nativeInput.files=dt.files;
                ensurePreviewImage().src = URL.createObjectURL(file);
                hideModal();
            },'image/jpeg',0.9);
        });
    }

    if (cancel) cancel.addEventListener('click', function(){ nativeInput.value=''; hideModal(); });

    if (rem) {
        rem.addEventListener('click', function(){
            if(!confirm('Remove profile photo?')) return;
            var f=document.createElement('form'); f.method='POST'; f.action='{{ route('profile.update') }}'; f.style.display='none';
            var t=document.createElement('input'); t.type='hidden'; t.name='_token'; t.value='{{ csrf_token() }}'; f.appendChild(t);
            var m=document.createElement('input'); m.type='hidden'; m.name='_method'; m.value='PATCH'; f.appendChild(m);
            var r=document.createElement('input'); r.type='hidden'; r.name='remove_photo'; r.value='1'; f.appendChild(r);
            document.body.appendChild(f); f.submit();
        });
    }
})();
</script>
@endsection
