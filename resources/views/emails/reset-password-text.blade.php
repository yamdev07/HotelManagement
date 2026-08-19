{{ __('emails.password_reset_title') }} · {{ config('app.name', 'checkinHub') }}

{{ __('emails.greeting') }}

{{ __('emails.password_reset_request_text') }}

{{ __('emails.open_link_to_reset', ['minutes' => $expire]) }}
{{ $resetUrl }}

{{ __('emails.password_reset_ignore') }}

© {{ date('Y') }} {{ config('app.name', 'checkinHub') }}
