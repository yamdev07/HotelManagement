Réservation confirmée · {{ $hotelName }}

Bonjour {{ $customerName }},

Votre réservation à {{ $hotelName }} est bien enregistrée :

- Chambre : {{ $roomNumber }}@if($roomType) ({{ $roomType }})@endif

- Arrivée : {{ $checkIn }}
- Départ  : {{ $checkOut }}
- Durée   : {{ $nights }} nuit(s)
- Total   : {{ number_format($total, 0, ',', ' ') }} FCFA
@if ($paid > 0)
- Déjà réglé   : {{ number_format($paid, 0, ',', ' ') }} FCFA
- Reste à payer : {{ number_format(max(0, $total - $paid), 0, ',', ' ') }} FCFA
@endif

Présentez votre nom à la réception le jour de votre arrivée.
@if ($hotelPhone)Pour toute question : {{ $hotelPhone }}@endif

À très bientôt !
{{ $hotelName }}
