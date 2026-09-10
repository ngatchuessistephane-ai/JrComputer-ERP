<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemActivity;
class NotificationPageController extends Controller
{
    public function index()
    {
        return view('notifications.index');
    }

    public function fetch(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->paginate(25);
        // + system activities
        return response()->json([
            'notifications' => $notifications,
            'system_activities' => SystemActivity::where('user_id', $user->id)
                ->orWhereNull('user_id')
                ->orderBy('created_at', 'desc')
                ->paginate(25),
        ]);
    }
}