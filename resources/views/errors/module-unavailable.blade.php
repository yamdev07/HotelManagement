<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('modules.unavailable_title') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card shadow-sm border-0" style="max-width: 560px;">
            <div class="card-body text-center p-5">
                @php
                    $hotel = auth()->user()?->hotel;
                    $moduleLabel = $moduleLabel ?? session('module_label', __('modules.this_feature'));
                    $planName = $planName ?? session('plan_name');
                @endphp

                <div class="mb-3">
                    <i class="fas fa-crown fa-3x" style="color:#f59e0b;"></i>
                </div>

                <h3 class="mb-3">{{ __('modules.unavailable_title') }}</h3>

                <p class="text-muted mb-3">
                    {{ __('modules.unavailable_intro', ['module' => $moduleLabel]) }}
                    @if($planName)
                        <br><span class="small">{{ __('modules.current_plan', ['plan' => $planName]) }}</span>
                    @endif
                </p>

                <div class="alert alert-warning text-start small">
                    <i class="fas fa-circle-info me-1"></i>
                    {{ __('modules.contact_admin') }}
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-sign-out-alt me-1"></i> {{ __('modules.logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
