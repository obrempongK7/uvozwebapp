<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\WalletService;
use Illuminate\Http\Request;

class ApiWalletController extends Controller {
    public function __construct(private WalletService $ws) {}
    public function index(Request $request) { $wallet = $this->ws->getOrCreate($request->user()); return response()->json($wallet); }
    public function transactions(Request $request) { return response()->json(\App\Models\WalletTransaction::where('user_id',$request->user()->id)->orderByDesc('created_at')->paginate(20)); }
}
