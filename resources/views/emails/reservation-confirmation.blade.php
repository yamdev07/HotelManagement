<!DOCTYPE html>
<html lang="fr">
<head><meta charset="utf-8"></head>
<body style="font-family: Arial, sans-serif; background:var(--surface, #f5f7fb); padding:24px; color:#0f172a;">
    <div style="max-width:560px;margin:0 auto;background:var(--white, #fff);border-radius:12px;overflow:hidden;border:1px solid #e2e8f0;">
        <div style="background:{{ $color }};color:#fff;padding:24px 28px;">
            <h2 style="margin:0;">Réservation confirmée ✔</h2>
            <div style="opacity:.9;font-size:14px;margin-top:4px;">{{ $hotelName }}</div>
        </div>
        <div style="padding:28px;">
            <p>Bonjour <strong>{{ $customerName }}</strong>,</p>
            <p>Votre réservation à <strong>{{ $hotelName }}</strong> est bien enregistrée. En voici le récapitulatif :</p>

            <table style="width:100%;border-collapse:collapse;margin:18px 0;">
                <tr>
                    <td style="padding:9px 0;color:#64748b;">Chambre</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ $roomNumber }}@if($roomType) · {{ $roomType }}@endif</td>
                </tr>
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">Arrivée</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ $checkIn }}</td>
                </tr>
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">Départ</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ $checkOut }}</td>
                </tr>
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">Durée</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ $nights }} nuit(s)</td>
                </tr>
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">Total du séjour</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ number_format($total, 0, ',', ' ') }} FCFA</td>
                </tr>
                @if ($paid > 0)
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">Déjà réglé</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;color:var(--g600);">{{ number_format($paid, 0, ',', ' ') }} FCFA</td>
                </tr>
                <tr style="border-top:1px solid #eef1f6;">
                    <td style="padding:9px 0;color:#64748b;">Reste à payer</td>
                    <td style="padding:9px 0;font-weight:bold;text-align:right;">{{ number_format(max(0, $total - $paid), 0, ',', ' ') }} FCFA</td>
                </tr>
                @endif
            </table>

            <p style="color:#64748b;font-size:14px;">
                Présentez simplement votre nom à la réception le jour de votre arrivée.
                @if ($hotelPhone) Pour toute question : <strong>{{ $hotelPhone }}</strong>.@endif
            </p>
            <p style="color:#64748b;font-size:14px;">Nous avons hâte de vous accueillir !</p>
        </div>
        <div style="padding:16px 28px;background:#f8fafc;color:#94a3b8;font-size:12px;">
            {{ $hotelName }} · confirmation envoyée automatiquement, merci de ne pas y répondre.
        </div>
    </div>
</body>
</html>
