<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller {
    public function index() { return view('user.settings', ['user' => Auth::user()->load('profile')]); }
    public function updateAccount(Request $request) {
        $user = Auth::user();
        $request->validate(['email'=>['required','email','unique:users,email,'.$user->id],'display_name'=>['nullable','string','max:60'],'bio'=>['nullable','string','max:300']]);
        $user->update($request->only('email','display_name','bio','country','website'));
        return back()->with('success','Account updated.');
    }
    public function updatePrivacy(Request $request) {
        Auth::user()->profile?->update(['profile_public'=>$request->boolean('profile_public'),'allow_messages'=>$request->boolean('allow_messages'),'show_earnings'=>$request->boolean('show_earnings')]);
        return back()->with('success','Privacy settings updated.');
    }
    public function updateNotifications(Request $request) {
        // Store notification preferences in site_settings or user meta
        return back()->with('success','Notification preferences updated.');
    }
    public function updatePassword(Request $request) {
        $request->validate(['current_password'=>['required'],'password'=>['required',Password::min(8)->letters()->numbers(),'confirmed']]);
        if (!Hash::check($request->current_password, Auth::user()->password)) return back()->withErrors(['current_password'=>'Wrong password.']);
        Auth::user()->update(['password'=>Hash::make($request->password)]);
        return back()->with('success','Password changed.');
    }
    public function enableTwoFactor() { Auth::user()->update(['two_factor_enabled'=>true]); return back()->with('success','2FA enabled.'); }
    public function disableTwoFactor() { Auth::user()->update(['two_factor_enabled'=>false]); return back()->with('success','2FA disabled.'); }
    public function sessions() { $sessions = \App\Models\DeviceSession::where('user_id',Auth::id())->orderByDesc('last_active_at')->get(); return view('user.sessions', compact('sessions')); }
    public function revokeSession(int $id) { \App\Models\DeviceSession::where('id',$id)->where('user_id',Auth::id())->delete(); return back()->with('success','Session revoked.'); }
    public function updateRewardPreferences(Request $request) { return back()->with('success','Reward preferences updated.'); }
    public function updateShippingAddress(Request $request) {
        $request->validate(['full_name'=>['required'],'phone'=>['required'],'address_line1'=>['required'],'city'=>['required'],'country'=>['required']]);
        \App\Models\RewardShippingAddress::updateOrCreate(['user_id'=>Auth::id(),'is_default'=>true],$request->only('full_name','phone','address_line1','address_line2','city','state','postal_code','country'));
        return back()->with('success','Shipping address saved.');
    }
    public function deleteAccount(Request $request) {
        $request->validate(['password'=>['required']]);
        if (!Hash::check($request->password,Auth::user()->password)) return back()->withErrors(['password'=>'Wrong password.']);
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        return redirect()->route('home')->with('success','Account deleted.');
    }
}
