<?php
// ---- ProfileController ----
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller {
    public function myProfile() {
        return redirect()->route('profile.show', Auth::user()->username);
    }
    public function show(string $username) {
        $profile = User::with(['profile','voicePosts' => fn($q) => $q->where('status','published')->where('visibility','public')->orderByDesc('created_at')->limit(12)])
            ->where('username', $username)->where('is_active', true)->where('is_banned', false)->firstOrFail();
        $isFollowing = Auth::check() && Auth::user()->isFollowing($profile);
        $podcasts = \App\Models\Podcast::where('user_id',$profile->id)->where('status','active')->orderByDesc('created_at')->limit(6)->get();
        return view('user.profile', compact('profile','isFollowing','podcasts'));
    }
    public function update(Request $request) {
        $user = Auth::user();
        $request->validate(['display_name'=>['nullable','string','max:60'],'bio'=>['nullable','string','max:300'],'country'=>['nullable','string','size:2'],'website'=>['nullable','url']]);
        $user->update($request->only('display_name','bio','country','website'));
        $user->profile?->update($request->only('location','birth_date','gender','language'));
        return back()->with('success','Profile updated.');
    }
    public function updateAvatar(Request $request) {
        $request->validate(['avatar'=>['required','image','max:5120']]);
        $path = $request->file('avatar')->store('avatars','public');
        Auth::user()->update(['avatar'=>$path]);
        return response()->json(['url' => asset('storage/'.$path)]);
    }
    public function updateCover(Request $request) {
        $request->validate(['cover'=>['required','image','max:10240']]);
        $path = $request->file('cover')->store('covers','public');
        Auth::user()->update(['cover_image'=>$path]);
        return response()->json(['url' => asset('storage/'.$path)]);
    }
    public function follow(User $user) {
        $me = Auth::user();
        if ($me->id === $user->id) return response()->json(['error'=>'Cannot follow yourself'],422);
        if (!$me->isFollowing($user)) {
            $me->following()->attach($user->id);
            $user->profile?->increment('followers_count');
            $me->profile?->increment('following_count');
        }
        return response()->json(['following'=>true,'count'=>$user->profile?->followers_count]);
    }
    public function unfollow(User $user) {
        $me = Auth::user();
        $me->following()->detach($user->id);
        $user->profile?->decrement('followers_count');
        $me->profile?->decrement('following_count');
        return response()->json(['following'=>false,'count'=>$user->profile?->followers_count]);
    }
}
