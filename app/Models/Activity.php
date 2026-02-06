<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Activitylog\Models\Activity as BaseActivity;

class Activity extends BaseActivity
{
    protected $appends = ['subject_url', 'model_icon', 'model_name'];

    protected function subjectUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->subject) {
                    return null;
                }

                $subject = $this->subject;

                // Détermine l'URL en fonction du type de modèle
                $class = get_class($subject);

                if ($class === \App\Models\Room::class) {
                    return route('room.show', $subject->id);
                } elseif ($class === \App\Models\Transaction::class) {
                    return route('transaction.show', $subject->id);
                } elseif ($class === \App\Models\Customer::class) {
                    return route('customer.show', $subject->id);
                } elseif ($class === \App\Models\User::class) {
                    return route('user.show', $subject->id);
                }
                // Ajoute d'autres modèles au besoin

                return null;
            }
        );
    }

    protected function modelIcon(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->subject) {
                    return 'fa-question-circle';
                }

                $class = get_class($this->subject);

                if ($class === \App\Models\Room::class) {
                    return 'fa-bed';
                } elseif ($class === \App\Models\Transaction::class) {
                    return 'fa-receipt';
                } elseif ($class === \App\Models\Customer::class) {
                    return 'fa-user';
                } elseif ($class === \App\Models\User::class) {
                    return 'fa-user-tie';
                }

                return 'fa-cube';
            }
        );
    }

    protected function modelName(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (! $this->subject) {
                    return 'Objet supprimé';
                }

                $class = get_class($this->subject);

                if ($class === \App\Models\Room::class) {
                    return 'Chambre';
                } elseif ($class === \App\Models\Transaction::class) {
                    return 'Réservation';
                } elseif ($class === \App\Models\Customer::class) {
                    return 'Client';
                } elseif ($class === \App\Models\User::class) {
                    return 'Utilisateur';
                }

                return class_basename($this->subject);
            }
        );
    }
}
