<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ApiUserController extends Controller {
    public function me(Request $request) { return response()->json($request->user()->load(['profile','wallet'])); }
    public function show(string $username) { $user = User::with(['profile'])->where('username',$username)->firstOrFail(); return response()->json($user); }
}
