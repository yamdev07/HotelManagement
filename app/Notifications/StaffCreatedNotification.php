<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/**
 * Notification envoyée à l'admin lorsqu'il crée un nouveau membre du personnel.
 */
class StaffCreatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public User $staff,
        public string $plainPassword
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Compte créé : ' . $this->staff->name . ' (' . $this->staff->role . ')',
            'url'     => route('staff.index'),
            'type'    => 'staff_created',
            'staff_name'  => $this->staff->name,
            'staff_email' => $this->staff->email,
            'staff_role'  => $this->staff->role,
        ];
    }
}
