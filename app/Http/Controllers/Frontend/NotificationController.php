<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NotificationSeen;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        $userId = auth()->id();
        $unseenNotifications = userNotification('unseen');

        foreach ($unseenNotifications as $notification) {
            NotificationSeen::updateOrCreate(
                ['user_id' => $userId, 'notification_id' => $notification->id],
                ['is_seen' => 1]
            );
        }

        return redirect()->back()->with('success', __('All notifications marked as read'));
    }

    public function markAsRead($id)
    {
        $userId = auth()->id();

        NotificationSeen::updateOrCreate(
            ['user_id' => $userId, 'notification_id' => $id],
            ['is_seen' => 1]
        );

        return redirect()->back()->with('success', __('Notification marked as read'));
    }
}
