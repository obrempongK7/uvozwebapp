<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\VoicePost;
use Illuminate\Http\Request;

class ApiPostController extends Controller {
    public function index() { return response()->json(VoicePost::with('user')->where('status','published')->where('visibility','public')->orderByDesc('published_at')->paginate(20)); }
    public function show(int $id) { return response()->json(VoicePost::with('user')->findOrFail($id)); }
    public function store(Request $request) {
        $request->validate(['title'=>['required','string'],'audio'=>['required','file','mimes:mp3,m4a,aac,wav']]);
        $path = $request->file('audio')->store('voice-posts','public');
        $post = VoicePost::create(['user_id'=>$request->user()->id,'title'=>$request->title,'description'=>$request->description,'audio_file'=>$path,'status'=>'published','visibility'=>$request->visibility??'public','published_at'=>now()]);
        return response()->json($post,201);
    }
}
