<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessagingController extends Controller {
    public function index() {
        $convos = Conversation::whereHas('participants', fn($q) => $q->where('user_id',Auth::id()))
            ->with(['participants','lastMessage'])->orderByDesc('last_message_at')->paginate(20);
        return view('user.messages', compact('convos'));
    }
    public function show(Conversation $conversation) {
        abort_unless($conversation->participants()->where('user_id',Auth::id())->exists(),403);
        $messages = $conversation->messages()->with('sender')->orderBy('created_at')->get();
        $conversation->participants()->where('user_id',Auth::id())->update(['last_read_at'=>now()]);
        return view('user.chat', compact('conversation','messages'));
    }
    public function newConversation(User $user) {
        $me = Auth::user();
        // Check if conversation exists
        $existing = Conversation::where('type','direct')
            ->whereHas('participants', fn($q) => $q->where('user_id',$me->id))
            ->whereHas('participants', fn($q) => $q->where('user_id',$user->id))
            ->first();
        if ($existing) return redirect()->route('message.show',$existing);
        $convo = Conversation::create(['type'=>'direct']);
        $convo->participants()->attach([$me->id,$user->id]);
        return redirect()->route('message.show',$convo);
    }
    public function send(Request $request, Conversation $conversation) {
        abort_unless($conversation->participants()->where('user_id',Auth::id())->exists(),403);
        $request->validate(['content'=>['required_without:file','nullable','string','max:2000']]);
        $file = null; $fileName = null;
        if ($request->hasFile('file')) { $file = $request->file('file')->store('messages','public'); $fileName = $request->file('file')->getClientOriginalName(); }
        $msg = Message::create(['conversation_id'=>$conversation->id,'sender_id'=>Auth::id(),'type'=>$file?'file':'text','content'=>$request->content,'file_path'=>$file,'file_name'=>$fileName]);
        $conversation->update(['last_message_id'=>$msg->id,'last_message_at'=>now()]);
        return response()->json(['success'=>true,'message'=>$msg->load('sender')]);
    }
    public function archive(Conversation $conversation) {
        $conversation->participants()->where('user_id',Auth::id())->update(['is_archived'=>true]);
        return response()->json(['success'=>true]);
    }
}
