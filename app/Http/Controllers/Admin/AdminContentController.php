<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\VoicePost;
use App\Models\StatusPost;
use Illuminate\Http\Request;

class AdminContentController extends Controller {
    public function reports(Request $request) { $reports = Report::with(['reporter','reportable'])->when($request->status,fn($q)=>$q->where('status',$request->status))->orderByDesc('created_at')->paginate(25); return view('admin.content.reports', compact('reports')); }
    public function action(Request $request, Report $report) { $report->update(['status'=>$request->action,'reviewed_by'=>\Auth::id(),'admin_notes'=>$request->notes]); return back()->with('success','Report updated.'); }
    public function voicePosts() { $posts = VoicePost::with('user')->orderByDesc('created_at')->paginate(25); return view('admin.content.voice', compact('posts')); }
    public function deleteVoicePost(VoicePost $post) { $post->delete(); return back()->with('success','Post deleted.'); }
    public function statusPosts() { $posts = StatusPost::with('user')->orderByDesc('created_at')->paginate(25); return view('admin.content.status', compact('posts')); }
    public function deleteStatusPost(StatusPost $post) { $post->delete(); return back()->with('success','Post deleted.'); }
}
