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

        // Issue #198 : on capture les notifications NON LUES avant de les marquer
        // lues, pour pouvoir les mettre en avant (« Nouveau ») sur cette page —
        // tout en vidant le badge rouge (issue #196).
        $newIds = $user->unreadNotifications->pluck('id')->all();

        $user->unreadNotifications->markAsRead();

        // Rafraîchit les relations en cache pour que le badge (non lues) tombe à 0
        // sur cette page même (sinon la collection déjà chargée garde l'ancien compte).
        $user->unsetRelation('notifications')
            ->unsetRelation('readNotifications')
            ->unsetRelation('unreadNotifications');

        $notifications = $user->notifications()->latest()->get();

        return view('notification.index', compact('notifications', 'newIds'));
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
