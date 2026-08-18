{{ __('emails.greeting_name', ['name' => $staffName]) }}

{!! __('emails.staff_account_created', ['role' => __('staff.role_' . strtolower($role)), 'hotel' => $hotelName]) !!}

{{ __('emails.your_credentials') }}
- {{ __('emails.email') }} : {{ $email }}
- {{ __('emails.password') }} : {{ $password }}
- {{ __('emails.role') }} : {{ __('staff.role_' . strtolower($role)) }}

{{ __('emails.login') }} : {{ $loginUrl }}

{{ __('emails.security_password_hint') }}

© {{ date('Y') }} {{ config('app.name', 'checkinHub') }}
