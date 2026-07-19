<?php

namespace App\Http\Controllers;

class NotificationsController extends Controller
{
    /**
     * Ouvre la page des notifications ET marque tout comme lu (issue #196 :
     * le badge rouge ne disparaissait pas car la consultation ne marquait rien).
     */
    public function index()
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();

        // Rafraîchit les relations en cache pour que le badge (non lues) tombe à 0
        // sur cette page même (sinon la collection déjà chargée garde l'ancien compte).
        $user->unsetRelation('notifications')
            ->unsetRelation('readNotifications')
            ->unsetRelation('unreadNotifications');

        return view('notification.index');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return redirect()->back();
    }

    public function routeTo($id)
    {
        $notification = auth()->user()->Notifications->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        return redirect($notification->data['url']);
    }
}
