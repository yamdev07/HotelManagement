<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; background:#f5f7fb; padding:24px; color:#0f172a;">
    <div style="max-width:560px;margin:0 auto;background:#fff;border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
        <div style="background:{{ $color }};color:#fff;padding:24px 28px;">
            <h2 style="margin:0;">{{ __('emails.reservation_confirmed') }} ✔</h2>
            <div style="opacity:.9;font-size:14px;margin-top:4px;">{{ $hotelName }}</div>
        </div>
        <div style="padding:28px;">
            <p>{!! __('emails.greeting_name', ['name' => $customerName]) !!}</p>
            <p>{!! __('emails.reservation_summary_intro', ['hotel' => $hotelName]) !!}</p>

            <table style="width:100%;border-collapse:collapse;margin:18px 0;">
                <tr>
                    <td style="padding:9px 0;color:#64748b;">{{ __('emails.room') }}</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ $roomNumber }}@if($roomType) · {{ $roomType }}@endif</td>
                </tr>
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">{{ __('emails.check_in') }}</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ $checkIn }}</td>
                </tr>
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">{{ __('emails.check_out') }}</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ $checkOut }}</td>
                </tr>
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">{{ __('emails.duration') }}</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ __('emails.nights', ['count' => $nights]) }}</td>
                </tr>
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">{{ __('emails.total_stay') }}</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ number_format($total, 0, ',', ' ') }} FCFA</td>
                </tr>
                @if ($paid > 0)
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">{{ __('emails.already_paid') }}</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;color:var(--g600);">{{ number_format($paid, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">{{ __('emails.balance_due') }}</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ number_format(max(0, $total - $paid), 0, ',', ' ') }} FCFA</td>
                </tr>
                @endif
            </table>

            <p style="color:#64748b;font-size:14px;">
                {{ __('emails.present_at_reception') }}
                @if ($hotelPhone) {{ __('emails.for_any_question') }} <strong>{{ $hotelPhone }}</strong>.@endif
            </p>
            <p style="color:#64748b;font-size:14px;">{{ __('emails.looking_forward') }}</p>
        </div>
        <div style="padding:16px 28px;background:#f8fafc;color:#94a3b8;font-size:12px;">
            {{ $hotelName }} · {{ __('emails.auto_confirmation_footer') }}
        </div>
    </div>
</body>
</html>
