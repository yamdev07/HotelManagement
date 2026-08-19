<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; background:#f5f7fb; padding:24px; color:#0f172a;">
    <div style="max-width:560px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
        <div style="background:#4f46e5;color:#fff;padding:24px 28px;">
            <h2 style="margin:0;">{{ __('emails.password_reset_title') }}</h2>
        </div>
        <div style="padding:28px;">
            <p>{{ __('emails.greeting') }}</p>
            <p>{!! __('emails.password_reset_request', ['email' => $email, 'app_name' => config('app.name', 'checkinHub')]) !!}</p>

            <p>{{ __('emails.password_reset_click_below') }}</p>

            <p style="margin:24px 0;">
                <a href="{{ $resetUrl }}"
                   style="display:inline-block;background:#4f46e5;color:#fff;text-decoration:none;padding:12px 22px;border-radius:8px;font-weight:bold;">
                    {{ __('emails.reset_my_password') }}
                </a>
            </p>

            <p style="color:#64748b;font-size:14px;">
                {{ __('emails.link_expires_in', ['minutes' => $expire]) }}
                {{ __('emails.copy_paste_link') }}<br>
                <a href="{{ $resetUrl }}" style="color:#4f46e5;word-break:break-all;">{{ $resetUrl }}</a>
            </p>

            <p style="margin-top:22px;color:#64748b;font-size:14px;">
                {{ __('emails.password_reset_ignore') }}
            </p>
        </div>
        <div style="padding:16px 28px;background:#f8fafc;color:#94a3b8;font-size:12px;">
            © {{ date('Y') }} {{ config('app.name', 'checkinHub') }}
        </div>
    </div>
</body>
</html>
