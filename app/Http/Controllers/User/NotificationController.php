<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller {
    public function index() {
        $notifications = Auth::user()->notifications()->orderByDesc('created_at')->paginate(20);
        return view('user.notifications', compact('notifications'));
    }
    public function markRead(string $id) {
        Auth::user()->notifications()->where('id',$id)->update(['read_at'=>now()]);
        return response()->json(['success'=>true]);
    }
    public function markAllRead() {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success','All notifications marked as read.');
    }
}
