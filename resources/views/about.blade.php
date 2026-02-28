@extends('layouts.app')

@section('title', 'Tentang Kami - DosiNyam')

@section('content')
<div class="sticker-container">
    <i class="bi bi-heart-fill sticker sticker-1"></i>
    <i class="bi bi-egg-fried sticker sticker-2"></i>
    <i class="bi bi-stars sticker sticker-3"></i>
    <i class="bi bi-emoji-smile sticker sticker-4"></i>
    <i class="bi bi-cup-hot sticker sticker-5"></i>
</div>

<div class="container py-5">

    {{-- Hero --}}
    <div class="text-center mb-5">
        <h1 class="fw-black shadow-text mb-2" style="font-size: clamp(2.5rem, 6vw, 4rem);">
            Tentang <span style="color: var(--fh-red);">Dosi</span><span style="color: var(--fh-green);">Nyam</span>
        </h1>
        <p class="fw-bold text-muted fs-5">Enak, Praktis, Terjangkau 🍱</p>
    </div>

    {{-- Two main cards --}}
    <div class="row g-4 mb-5">

        {{-- Card 1: Kenapa namanya DosiNyam? --}}
        <div class="col-lg-6">
            <div class="brand-card p-5 h-100" style="background: #fff9f0; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
                <h2 class="fw-black mb-4" style="font-size: 1.8rem;">
                    Kenapa namanya <span style="color: var(--fh-red);">DosiNyam</span>? 🤔
                </h2>

                <p class="fw-bold text-dark mb-4" style="line-height: 1.8;">
                    <span style="color: var(--fh-red);" class="fw-black">Dosi</span>
                    (dari kata <strong>Dosirak / 도시락</strong>) yang artinya kotak makan siang dalam bahasa Korea.
                    Kami hadir buat bawa kehangatan bekal rumah ke setiap aktivitas padatmu.
                    Dari bahan pilihan dengan rasa yang otentik, semuanya dibungkus spesial buat kamu!
                </p>

                <div class="p-4 rounded-4 border border-2 border-dark" style="background: #fff3cd;">
                    <p class="fw-bold text-dark mb-0" style="line-height: 1.8;">
                        <span style="color: var(--fh-green);" class="fw-black">Nyam!</span>
                        adalah suara <span style="color: var(--fh-red);" class="fw-black">kepuasan</span>
                        setiap kali kamu menikmati masakan yang enak dan akrab di lidah.
                        Gabungan keduanya jadi komitmen kami buat kasih kamu
                        <span style="color: var(--fh-red);" class="fw-black">bekal yang bikin nagih!</span>
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 2: Apa sih yang bisa DosiNyam offer? --}}
        <div class="col-lg-6">
            <div class="brand-card p-5 h-100" style="background: #f0f8ff; border: 3px solid #000; box-shadow: 8px 8px 0 #000;">
                <h2 class="fw-black mb-4" style="font-size: 1.8rem;">
                    Apa sih yang bisa DosiNyam <em>offer</em>? 🍛
                </h2>

                <p class="fw-bold text-dark mb-4" style="line-height: 1.8;">
                    Gak sempat masak? Males ngantre makan siang?
                    Dosinyam jualannya bukan cuma nasi box biasa, tapi solusi praktis buat hari-harimu.
                </p>

                <div class="p-4 rounded-4 border border-2 border-dark bg-white mb-3">
                    <div class="d-flex align-items-start gap-3">
                        <span style="font-size: 1.5rem; flex-shrink: 0;">🍽️</span>
                        <p class="mb-0 fw-bold text-dark" style="line-height: 1.7;">
                            Kami menyediakan berbagai pilihan menu
                            <span style="color: var(--fh-red);" class="fw-black">masakan rumah</span>
                            yang lengkap, porsi pas, dan dimasak segar setiap harinya.
                        </p>
                    </div>
                </div>

                <div class="p-4 rounded-4 border border-2 border-dark bg-white">
                    <div class="d-flex align-items-start gap-3">
                        <span style="font-size: 1.5rem; flex-shrink: 0;">✅</span>
                        <p class="mb-0 fw-bold text-dark" style="line-height: 1.7;">
                            Bekal higienis yang siap nemenin kamu rapat, ngerjain tugas, atau sekadar istirahat
                            tanpa ribet dan bisa disantap dimana saja!
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Values row --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="brand-card p-4 text-center h-100 bg-white" style="border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
                <div style="font-size: 3rem;" class="mb-3">🥘</div>
                <h5 class="fw-black text-dark mb-2">Masakan Rumah</h5>
                <p class="fw-bold text-muted mb-0 small">Rasa otentik ala dapur sendiri, bukan produksi massal.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="brand-card p-4 text-center h-100 bg-white" style="border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
                <div style="font-size: 3rem;" class="mb-3">💚</div>
                <h5 class="fw-black text-dark mb-2">Higienis & Segar</h5>
                <p class="fw-bold text-muted mb-0 small">Dimasak fresh setiap hari dari bahan-bahan pilihan.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="brand-card p-4 text-center h-100 bg-white" style="border: 3px solid #000; box-shadow: 6px 6px 0 #000;">
                <div style="font-size: 3rem;" class="mb-3">💰</div>
                <h5 class="fw-black text-dark mb-2">Harga Terjangkau</h5>
                <p class="fw-bold text-muted mb-0 small">Porsi pas di kantong, rasa gak pas-pasan.</p>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="text-center">
        <a href="{{ route('menus.index') }}" class="brand-btn brand-btn-primary py-3 px-5 fs-5 text-decoration-none">
            <i class="bi bi-cart-plus me-2"></i>Yuk, Pesan Sekarang!
        </a>
    </div>

</div>
@endsection
