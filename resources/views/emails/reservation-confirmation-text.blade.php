{{ __('emails.reservation_confirmed') }} · {{ $hotelName }}

{{ __('emails.greeting_name', ['name' => $customerName]) }}

{{ __('emails.reservation_summary_intro', ['hotel' => $hotelName]) }}

- {{ __('emails.room') }} : {{ $roomNumber }}@if($roomType) ({{ $roomType }})@endif

- {{ __('emails.check_in') }} : {{ $checkIn }}
- {{ __('emails.check_out') }}  : {{ $checkOut }}
- {{ __('emails.duration') }}   : {{ __('emails.nights', ['count' => $nights]) }}
- {{ __('emails.total_stay') }}   : {{ number_format($total, 0, ',', ' ') }} FCFA
@if ($paid > 0)
- {{ __('emails.already_paid') }}   : {{ number_format($paid, 0, ',', ' ') }} FCFA
- {{ __('emails.balance_due') }} : {{ number_format(max(0, $total - $paid), 0, ',', ' ') }} FCFA
@endif

{{ __('emails.present_at_reception') }}
@if ($hotelPhone){{ __('emails.for_any_question') }} {{ $hotelPhone }}@endif

{{ __('emails.farewell') }}
{{ $hotelName }}
