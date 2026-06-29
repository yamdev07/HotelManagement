@extends('public.layout')
@section('title', 'Services')

@section('content')
    @php $cover = $hotel->coverUrl(); $services = $hotel->siteServices(); @endphp
    <header class="page-head {{ $cover ? 'has-img' : '' }}" @if($cover) style="background-image:url('{{ $cover }}')" @endif>
        @if($cover)<div class="ov"></div>@endif
        <div class="container">
            <div class="eyebrow mb-2" style="color:#fff;opacity:.85;">L'art de recevoir</div>
            <h1 class="display-serif" style="font-size:clamp(2.4rem,6vw,4rem);">Nos services</h1>
        </div>
    </header>

    <section class="section">
        <div class="container">
            <div class="row g-4">
                @foreach ($services as $i => $svc)
                    <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="{{ ($i % 3) * 100 }}">
                        <div class="svc-card lift h-100" style="box-shadow:0 10px 40px -28px rgba(0,0,0,.4);">
                            <div class="svc-ico mb-3"><i class="fas {{ $svc['icon'] ?? 'fa-star' }}"></i></div>
                            <h4 class="serif mb-2" style="font-size:1.35rem;">{{ $svc['title'] ?? '' }}</h4>
                            <p class="small mb-0" style="opacity:.85;">{{ $svc['description'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
