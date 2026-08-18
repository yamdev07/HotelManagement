<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; background:#f5f7fb; padding:24px; color:#0f172a;">
    <div style="max-width:560px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
        <div style="background:#4f46e5;color:#fff;padding:24px 28px;">
            <h2 style="margin:0;">{{ __('emails.welcome_on', ['app_name' => config('app.name', 'checkinHub')]) }}</h2>
        </div>
        <div style="padding:28px;">
            <p>{!! __('emails.greeting_name', ['name' => $staffName]) !!}</p>
            <p>{!! __('emails.staff_account_created', ['role' => __('staff.role_' . strtolower($role)), 'hotel' => $hotelName]) !!}</p>
            <p>{{ __('emails.your_credentials') }}</p>

            <table style="width:100%;border-collapse:collapse;margin:18px 0;">
                <tr>
                    <td style="padding:10px 0;color:#64748b;">{{ __('emails.email') }}</td>
                    <td style="padding:10px 0;font-weight:bold;">{{ $email }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#64748b;">{{ __('emails.password') }}</td>
                    <td style="padding:10px 0;font-weight:bold;letter-spacing:1px;">{{ $password }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#64748b;">{{ __('emails.role') }}</td>
                    <td style="padding:10px 0;font-weight:bold;">{{ __('staff.role_' . strtolower($role)) }}</td>
                </tr>
            </table>

            <a href="{{ $loginUrl }}"
               style="display:inline-block;background:#4f46e5;color:#fff;text-decoration:none;padding:12px 22px;border-radius:8px;font-weight:bold;">
                {{ __('emails.login') }}
            </a>

            <p style="margin-top:22px;color:#64748b;font-size:14px;">
                {{ __('emails.security_password_hint') }}
            </p>
        </div>
        <div style="padding:16px 28px;background:#f8fafc;color:#94a3b8;font-size:12px;">
            © {{ date('Y') }} {{ config('app.name', 'checkinHub') }}
        </div>
    </div>
</body>
</html>
