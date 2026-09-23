<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $heading }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f5fb;font-family:Arial,Helvetica,sans-serif;color:#1f2733;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f5fb;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:520px;background:#ffffff;border-radius:14px;overflow:hidden;box-shadow:0 8px 30px -18px rgba(20,20,60,.5);">
                    <tr>
                        <td style="background:linear-gradient(90deg,#7c83ff,#b06bff);padding:22px 26px;color:#ffffff;font-size:18px;font-weight:bold;">
                            check<span style="opacity:.85">inHub</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 26px 8px;">
                            <p style="margin:0 0 4px;font-size:13px;color:#8891a3;">Bonjour {{ $hotelName }},</p>
                            <h1 style="margin:0 0 16px;font-size:22px;color:#171b2e;">{{ $heading }}</h1>
                            @foreach ($lines as $line)
                                <p style="margin:0 0 12px;font-size:15px;line-height:1.6;color:#3b4353;">{{ $line }}</p>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 26px 30px;">
                            <a href="{{ $ctaUrl }}" style="display:inline-block;background:#7c83ff;color:#ffffff;text-decoration:none;font-weight:bold;font-size:15px;padding:13px 24px;border-radius:10px;">{{ $ctaLabel }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 26px;background:#fafbff;border-top:1px solid #eef0f7;font-size:12px;color:#9aa1ad;">
                            checkinHub · La gestion hôtelière, pensée pour l'Afrique.<br>
                            Vous recevez cet email car vous avez démarré un essai gratuit.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
