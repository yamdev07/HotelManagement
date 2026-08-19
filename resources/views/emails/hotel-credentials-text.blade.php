{{ __('emails.welcome_on', ['app_name' => config('app.name', 'checkinHub')]) }}

{{ __('emails.hotel_establishment_ready', ['hotel' => $hotelName]) }}

{{ __('emails.your_admin_credentials') }}
- {{ __('emails.email') }} : {{ $email }}
- {{ __('emails.password') }} : {{ $password }}

{{ __('emails.login_link') }} {{ $loginUrl }}

{{ __('emails.security_password_hint') }}

·
{{ config('app.name', 'checkinHub') }}
