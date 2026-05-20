<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\LiveRoom;
use App\Models\LiveRoomParticipant;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LiveRoomController extends Controller {
    public function __construct(private WalletService $ws) {}
    public function index() { $rooms = LiveRoom::with('host')->whereIn('status',['live','scheduled'])->orderByDesc('listeners_count')->paginate(20); return view('live.index', compact('rooms')); }
    public function create() { return view('live.create'); }
    public function store(Request $request) {
        $request->validate(['title'=>['required','string','max:200'],'type'=>['required','in:public,private,password_protected']]);
        $room = LiveRoom::create(['host_id'=>Auth::id(),'title'=>$request->title,'description'=>$request->description,'type'=>$request->type,'password'=>$request->password?bcrypt($request->password):null,'is_ticketed'=>$request->boolean('is_ticketed'),'ticket_price'=>$request->ticket_price??0,'max_participants'=>$request->max_participants,'room_token'=>Str::random(32),'country'=>Auth::user()->country,'tags'=>$request->tags?explode(',',$request->tags):null,'status'=>$request->schedule?'scheduled':'live','scheduled_at'=>$request->scheduled_at]);
        if ($room->status==='live') { $room->update(['started_at'=>now()]); LiveRoomParticipant::create(['room_id'=>$room->id,'user_id'=>Auth::id(),'role'=>'host']); }
        return redirect()->route('live.show',$room);
    }
    public function show(LiveRoom $liveRoom) { $liveRoom->load('host','participants.user'); $myRole = LiveRoomParticipant::where('room_id',$liveRoom->id)->where('user_id',Auth::id())->value('role'); return view('live.room', compact('liveRoom','myRole')); }
    public function join(Request $request, LiveRoom $liveRoom) {
        if ($liveRoom->type==='password_protected'&&!password_verify($request->password,$liveRoom->password)) return response()->json(['error'=>'Wrong password'],422);
        LiveRoomParticipant::firstOrCreate(['room_id'=>$liveRoom->id,'user_id'=>Auth::id()],['role'=>'listener']);
        $liveRoom->increment('listeners_count');
        if ($liveRoom->listeners_count>$liveRoom->peak_listeners) $liveRoom->update(['peak_listeners'=>$liveRoom->listeners_count]);
        return response()->json(['success'=>true,'role'=>'listener']);
    }
    public function leave(LiveRoom $liveRoom) {
        LiveRoomParticipant::where('room_id',$liveRoom->id)->where('user_id',Auth::id())->update(['is_active'=>false,'left_at'=>now()]);
        $liveRoom->decrement('listeners_count');
        $pts = $this->ws->getRewardRate('live_room_listen',Auth::user()->country);
        if ($pts>0) $this->ws->awardPoints(Auth::user(),$pts,'points_earned','Participated in live room',$liveRoom);
        return response()->json(['success'=>true]);
    }
    public function start(LiveRoom $liveRoom) { abort_if($liveRoom->host_id!==Auth::id(),403); $liveRoom->update(['status'=>'live','started_at'=>now()]); return response()->json(['success'=>true]); }
    public function end(LiveRoom $liveRoom) { abort_if($liveRoom->host_id!==Auth::id(),403); $liveRoom->update(['status'=>'ended','ended_at'=>now()]); return response()->json(['success'=>true]); }
    public function sendMessage(Request $request, LiveRoom $liveRoom) {
        $request->validate(['content'=>['required','string','max:500']]);
        $msg = \App\Models\LiveRoomMessage::create(['room_id'=>$liveRoom->id,'user_id'=>Auth::id(),'type'=>'text','content'=>$request->content,'created_at'=>now()]);
        return response()->json(['success'=>true,'message'=>$msg->load('user')]);
    }
    public function raiseHand(LiveRoom $liveRoom) { LiveRoomParticipant::where('room_id',$liveRoom->id)->where('user_id',Auth::id())->update(['has_raised_hand'=>true]); return response()->json(['success'=>true]); }
    public function inviteSpeaker(LiveRoom $liveRoom, \App\Models\User $user) { abort_if($liveRoom->host_id!==Auth::id(),403); LiveRoomParticipant::where('room_id',$liveRoom->id)->where('user_id',$user->id)->update(['role'=>'speaker']); return response()->json(['success'=>true]); }
}
