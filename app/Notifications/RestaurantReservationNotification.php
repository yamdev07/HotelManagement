<?php

namespace App\Notifications;

use App\Models\RestaurantReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RestaurantReservationNotification extends Notification
{
    use Queueable;

    public function __construct(public RestaurantReservation $reservation) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Nouvelle réservation restaurant — '
                . $this->reservation->name
                . ' · ' . $this->reservation->persons . ' pers.'
                . ' le ' . \Carbon\Carbon::parse($this->reservation->reservation_date)->format('d/m/Y')
                . ' à ' . \Carbon\Carbon::parse($this->reservation->reservation_time)->format('H:i'),
            'url'              => route('restaurant.orders'),
            'type'             => 'restaurant_reservation',
            'reservation_id'   => $this->reservation->id,
            'customer_name'    => $this->reservation->name,
            'phone'            => $this->reservation->phone,
            'date'             => \Carbon\Carbon::parse($this->reservation->reservation_date)->format('d/m/Y'),
            'time'             => \Carbon\Carbon::parse($this->reservation->reservation_time)->format('H:i'),
            'persons'          => $this->reservation->persons,
            'table_type'       => $this->reservation->table_type,
        ];
    }
}
