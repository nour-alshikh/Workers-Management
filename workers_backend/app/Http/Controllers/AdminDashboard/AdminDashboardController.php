<?php

namespace App\Http\Controllers\AdminDashboard;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $admin = Admin::find(auth()->guard('admins')->id());
        return response()->json([
            'notifications' => $admin->notifications
        ]);
    }
    public function unread()
    {
        $admin = Admin::find(auth()->guard('admins')->id());
        return response()->json([
            'notifications' => $admin->unreadNotifications
        ]);
    }

    public function markAllAsRead()
    {
        $admin = Admin::find(auth()->guard('admins')->id());

        foreach ($admin->notifications as $notification) {
            $notification->markAsRead();
        }
        return response()->json([
            'message' => "Marked All as Read"
        ]);
    }

    public function markAsRead($id)
    {
        $admin = Admin::find(auth()->guard('admins')->id());
        foreach ($admin->notifications as $notification) {
            if ($notification->id == $id) {
                $notification->markAsRead();
            }
        }
        return response()->json([
            'message' => "Notification Read"
        ]);
    }

    public function deleteAll()
    {
        $admin = Admin::find(auth()->guard('admins')->id());

        $admin->notifications()->delete();
        return response()->json([
            'message' => "All Notifications Are Deleted"
        ]);
    }
    public function deleteNotification($id)
    {
        DB::table('notifications')->where('id', $id)->delete();
        return response()->json([
            'message' => "Notification Deleted"
        ]);
    }
}
