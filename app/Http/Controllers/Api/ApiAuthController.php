<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApiAuthController extends Controller {
    public function register(Request $request) {
        $request->validate(['username'=>['required','min:3','alpha_num','unique:users'],'email'=>['required','email','unique:users'],'password'=>['required','min:8']]);
        $user = User::create(['username'=>strtolower($request->username),'email'=>$request->email,'password'=>Hash::make($request->password),'display_name'=>$request->username,'referral_code'=>Str::upper(Str::random(8))]);
        Profile::create(['user_id'=>$user->id]);
        Wallet::create(['user_id'=>$user->id,'today_date'=>now()->toDateString()]);
        $token = $user->createToken('api')->plainTextToken;
        return response()->json(['token'=>$token,'user'=>$user],201);
    }
    public function login(Request $request) {
        $request->validate(['email'=>['required','email'],'password'=>['required']]);
        if (!Auth::attempt(['email'=>$request->email,'password'=>$request->password])) {
            return response()->json(['message'=>'Invalid credentials'],401);
        }
        $user = Auth::user();
        $token = $user->createToken('api')->plainTextToken;
        return response()->json(['token'=>$token,'user'=>$user]);
    }
    public function logout(Request $request) { $request->user()->currentAccessToken()->delete(); return response()->json(['message'=>'Logged out']); }
}
