<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $notifications = Notification::where('notifier_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $unread_notification = Notification::where('read_at', null)
            ->count();
        return response()->json([
            'data' => $notifications,
            "unread_notification" => $unread_notification
        ]);
    }
}
