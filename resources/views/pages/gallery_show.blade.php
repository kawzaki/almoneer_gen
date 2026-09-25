@extends('layouts.app')

@section('title', $album->title . ' | ألبوم الصور')

@push('styles')
<style>
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(60px) scale(0.96);
        }
        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-60px) scale(0.96);
        }
        to {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }
    @keyframes zoomOpen {
        from {
            opacity: 0;
            transform: scale(0.90);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    .slide-next {
        animation: slideInLeft 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .slide-prev {
        animation: slideInRight 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .zoom-open {
        animation: zoomOpen 0.32s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    /* Thumbnail scrollbar */
    .thumb-strip::-webkit-scrollbar {
        height: 6px;
    }
    .thumb-strip::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 9999px;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8">

    <!-- مسار التنقل -->
    <div class="flex items-center gap-2 text-xs text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-emerald-800">الرئيسية</a>
        <span>/</span>
        <a href="{{ route('gallery.index') }}" class="hover:text-emerald-800">ألبوم الصور</a>
        @if($album->parent)
            <span>/</span>
            <a href="{{ route('gallery.show', $album->parent->slug) }}" class="hover:text-emerald-800">{{ $album->parent->title }}</a>
        @endif
        <span>/</span>
        <span class="text-emerald-950 font-bold truncate">{{ $album->title }}</span>
    </div>

    <!-- ترويسة الألبوم -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-wrap items-center gap-2">
            @if($album->parent)
                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-bold flex items-center gap-1">
                    <i class="fa-solid fa-folder text-[10px]"></i>
                    {{ $album->parent->title }}
                </span>
            @endif

            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                @if($album->event_date)
                    <span class="inline-flex items-center gap-0.5" dir="rtl">
                        <span>{{ $album->event_date->format('d') }}</span>/<span>{{ $album->event_date->format('m') }}</span>/<span>{{ $album->event_date->format('Y') }}</span>
                    </span>
                @else
                    توثيق مصور
                @endif
            </span>

            <span class="px-3 py-1 rounded-full bg-gold-50 text-gold-800 text-xs font-bold font-mono">
                {{ $album->items->count() }} صورة
            </span>
        </div>

        <h1 class="text-2xl sm:text-3xl font-bold font-scholarly text-slate-900 leading-tight">
            {{ $album->title }}
        </h1>

        @if($album->description)
        <p class="text-sm text-slate-600 leading-relaxed font-light max-w-4xl">
            {{ $album->description }}
        </p>
        @endif
    </div>

    <!-- شبكة الصور (Images Grid) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @forelse($album->items as $index => $item)
        <div class="bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-lg transition-all duration-300 group cursor-pointer hover:-translate-y-1" 
             onclick="openLightbox({{ $index }})">
            <div class="h-44 sm:h-48 bg-slate-100 overflow-hidden relative">
                <img src="{{ $item->url }}" alt="{{ $item->caption ?? $album->title }}" class="w-full h-full object-cover group-hover:scale-108 transition duration-500">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/35 transition-all duration-300 flex items-center justify-center">
                    <div class="w-10 h-10 rounded-full bg-white/90 text-emerald-950 flex items-center justify-center opacity-0 group-hover:opacity-100 group-hover:scale-100 scale-75 transition-all duration-300 shadow-md">
                        <i class="fa-solid fa-magnifying-glass-plus text-sm"></i>
                    </div>
                </div>
                <div class="absolute bottom-2 left-2 px-2 py-0.5 rounded-md bg-black/60 backdrop-blur-xs text-white text-[10px] font-mono">
                    {{ $index + 1 }}
                </div>
            </div>
            @if($item->caption)
            <div class="p-2.5 text-xs text-slate-700 bg-white border-t border-slate-100 line-clamp-1 leading-snug">
                {{ $item->caption }}
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-slate-200 text-slate-500 space-y-2">
            <i class="fa-solid fa-camera text-3xl text-slate-300"></i>
            <p>سيتم رفع صور هذا الألبوم قريباً.</p>
        </div>
        @endforelse
    </div>

</div>

<!-- نافذة المعاينة المكبرة التفاعلية (Interactive Animated Lightbox Modal) -->
<div id="lightboxModal" class="fixed inset-0 z-50 bg-black/95 hidden items-center justify-center backdrop-blur-md select-none transition-opacity duration-300" onclick="handleBackdropClick(event)">
    
    <!-- شريط التحكم العلوي -->
    <div class="absolute top-0 inset-x-0 p-4 sm:p-6 flex items-center justify-between text-white z-20 bg-gradient-to-b from-black/80 to-transparent">
        <!-- العداد وعنوان الألبوم -->
        <div class="flex items-center gap-3">
            <span id="lightboxCounter" class="px-3 py-1 rounded-full bg-white/15 text-gold-300 font-mono text-xs font-bold backdrop-blur-xs border border-white/10">
                1 / 1
            </span>
            <span class="text-xs sm:text-sm text-slate-300 font-light truncate max-w-[200px] sm:max-w-md hidden sm:inline">
                {{ $album->title }}
            </span>
        </div>

        <!-- أزرار الإغلاق وتكبير الشاشة -->
        <div class="flex items-center gap-2">
            <button type="button" onclick="toggleFullScreen()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition" title="ملء الشاشة">
                <i class="fa-solid fa-expand text-sm"></i>
            </button>
            <button type="button" onclick="closeLightbox()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-red-600/80 text-white flex items-center justify-center transition" title="إغلاق (Esc)">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
    </div>

    <!-- زر السابق (Previous Button) - اليمين في البيئة العربية RTL -->
    <button type="button" id="prevBtn" onclick="prevPhoto(event)" 
            class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white/10 hover:bg-white/25 active:scale-95 text-white flex items-center justify-center backdrop-blur-md border border-white/15 shadow-2xl transition-all duration-200 group"
            title="الصورة السابقة (السهم الأيمن)">
        <i class="fa-solid fa-chevron-right text-lg sm:text-xl group-hover:scale-110 transition"></i>
    </button>

    <!-- زر التالي (Next Button) - اليسار في البيئة العربية RTL -->
    <button type="button" id="nextBtn" onclick="nextPhoto(event)" 
            class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 z-20 w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white/10 hover:bg-white/25 active:scale-95 text-white flex items-center justify-center backdrop-blur-md border border-white/15 shadow-2xl transition-all duration-200 group"
            title="الصورة التالية (السهم الأيسر)">
        <i class="fa-solid fa-chevron-left text-lg sm:text-xl group-hover:scale-110 transition"></i>
    </button>

    <!-- حاوية الصورة الحالية والمتحركة -->
    <div class="relative max-w-5xl w-full h-full flex flex-col items-center justify-center p-4 sm:p-16 z-10">
        <div class="relative max-h-[75vh] sm:max-h-[80vh] flex items-center justify-center overflow-hidden rounded-2xl">
            <img id="lightboxImg" src="" alt="" 
                 class="max-h-[75vh] sm:max-h-[80vh] max-w-full rounded-2xl shadow-2xl object-contain ring-1 ring-white/10 transition-transform duration-300">
        </div>

        <!-- نص التوضيح والشرح أسفل الصورة -->
        <div class="mt-4 text-center max-w-2xl px-4 min-h-[3rem]">
            <p id="lightboxCaption" class="text-white text-xs sm:text-sm md:text-base font-medium drop-shadow-md leading-relaxed"></p>
        </div>
    </div>

    <!-- شريط المعاينة المصغرة بالأسفل (Thumbnail Strip) -->
    <div class="absolute bottom-2 inset-x-0 z-20 flex justify-center px-4">
        <div class="thumb-strip max-w-3xl overflow-x-auto flex items-center gap-2 p-2 bg-black/60 backdrop-blur-md rounded-2xl border border-white/10">
            @foreach($album->items as $idx => $thumb)
            <button type="button" onclick="goToPhoto({{ $idx }})" 
                    class="thumb-btn flex-shrink-0 w-12 h-10 rounded-lg overflow-hidden border-2 transition-all duration-200 opacity-60 hover:opacity-100 focus:outline-none"
                    data-index="{{ $idx }}"
                    title="{{ $thumb->caption ?? 'صورة ' . ($idx + 1) }}">
                <img src="{{ $thumb->url }}" alt="" class="w-full h-full object-cover">
            </button>
            @endforeach
        </div>
    </div>

</div>

<script>
    const galleryPhotos = [
        @foreach($album->items as $item)
        {
            url: "{{ $item->url }}",
            caption: "{{ addslashes($item->caption ?? $album->title) }}"
        },
        @endforeach
    ];

    let currentIndex = 0;
    const modal = document.getElementById('lightboxModal');
    const imgElement = document.getElementById('lightboxImg');
    const captionElement = document.getElementById('lightboxCaption');
    const counterElement = document.getElementById('lightboxCounter');
    const thumbButtons = document.querySelectorAll('.thumb-btn');

    function openLightbox(index) {
        if (!galleryPhotos.length) return;
        currentIndex = index;
        updateModalPhoto('open');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        if (document.fullscreenElement) {
            document.exitFullscreen();
        }
    }

    function handleBackdropClick(e) {
        // Close only if clicking the background, not the image or controls
        if (e.target.id === 'lightboxModal') {
            closeLightbox();
        }
    }

    function updateModalPhoto(direction = 'fade') {
        const photo = galleryPhotos[currentIndex];
        if (!photo) return;

        // Reset animation classes
        imgElement.classList.remove('slide-next', 'slide-prev', 'zoom-open');
        void imgElement.offsetWidth; // Trigger reflow to restart CSS animation

        if (direction === 'next') {
            imgElement.classList.add('slide-next');
        } else if (direction === 'prev') {
            imgElement.classList.add('slide-prev');
        } else {
            imgElement.classList.add('zoom-open');
        }

        imgElement.src = photo.url;
        imgElement.alt = photo.caption;
        captionElement.textContent = photo.caption;
        counterElement.textContent = `${currentIndex + 1} / ${galleryPhotos.length}`;

        // Update active thumbnail
        thumbButtons.forEach((btn, idx) => {
            if (idx === currentIndex) {
                btn.classList.remove('border-transparent', 'opacity-60');
                btn.classList.add('border-gold-400', 'opacity-100', 'scale-105');
                btn.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            } else {
                btn.classList.remove('border-gold-400', 'opacity-100', 'scale-105');
                btn.classList.add('border-transparent', 'opacity-60');
            }
        });
    }

    function nextPhoto(e) {
        if (e) e.stopPropagation();
        if (!galleryPhotos.length) return;
        currentIndex = (currentIndex + 1) % galleryPhotos.length;
        updateModalPhoto('next');
    }

    function prevPhoto(e) {
        if (e) e.stopPropagation();
        if (!galleryPhotos.length) return;
        currentIndex = (currentIndex - 1 + galleryPhotos.length) % galleryPhotos.length;
        updateModalPhoto('prev');
    }

    function goToPhoto(index) {
        if (index === currentIndex) return;
        const dir = index > currentIndex ? 'next' : 'prev';
        currentIndex = index;
        updateModalPhoto(dir);
    }

    function toggleFullScreen() {
        if (!document.fullscreenElement) {
            modal.requestFullscreen().catch(err => console.log(err));
        } else {
            document.exitFullscreen();
        }
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (modal.classList.contains('hidden')) return;

        if (e.key === 'Escape') {
            closeLightbox();
        } else if (e.key === 'ArrowLeft') {
            // Left arrow in RTL goes to NEXT photo
            nextPhoto();
        } else if (e.key === 'ArrowRight') {
            // Right arrow in RTL goes to PREVIOUS photo
            prevPhoto();
        }
    });

    // Touch Swipe Support for mobile devices
    let touchStartX = 0;
    let touchEndX = 0;

    modal.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    modal.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const threshold = 50; // minimum distance in px
        const diff = touchEndX - touchStartX;
        if (Math.abs(diff) > threshold) {
            if (diff < 0) {
                // Swiped Left -> Next
                nextPhoto();
            } else {
                // Swiped Right -> Prev
                prevPhoto();
            }
        }
    }
</script>
@endsection
