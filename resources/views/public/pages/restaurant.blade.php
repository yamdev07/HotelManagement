@extends('public.layout')
@section('title', 'Restaurant')

@section('content')
    @php $cover = $hotel->coverUrl(); @endphp
    <header class="page-head {{ $cover ? 'has-img' : '' }}" @if($cover) style="background-image:url('{{ $cover }}')" @endif>
        @if($cover)<div class="ov"></div>@endif
        <div class="container">
            <div class="eyebrow mb-2" style="color:#fff;opacity:.85;">Gastronomie</div>
            <h1 class="display-serif" style="font-size:clamp(2.4rem,6vw,4rem);">Notre restaurant</h1>
        </div>
    </header>

    <section class="section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <p class="text-secondary" style="max-width:600px;margin-inline:auto;font-size:1.1rem;">Une cuisine soignée, des produits choisis, une carte qui évolue au fil des saisons.</p>
            </div>

            @if ($menus->isEmpty())
                <p class="text-center text-secondary">Notre carte sera bientôt dévoilée.</p>
            @else
                <div class="row g-4 justify-content-center" style="max-width:900px;margin-inline:auto;">
                    @foreach ($menus as $i => $menu)
                        <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ ($i % 2) * 100 }}">
                            <div class="d-flex justify-content-between align-items-baseline pb-3" style="border-bottom:1px solid #e8e4dd;">
                                <div class="pe-3">
                                    <h4 class="serif mb-1" style="font-size:1.3rem;">{{ $menu->name }}</h4>
                                    @if ($menu->description)<p class="small text-secondary mb-0">{{ \Illuminate\Support\Str::limit($menu->description, 80) }}</p>@endif
                                </div>
                                <span class="serif text-c" style="font-size:1.3rem;white-space:nowrap;">{{ number_format($menu->price, 0, ',', ' ') }} {{ $hotel->currency }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
