<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller {
    public function __construct(private WalletService $walletService) {}
    public function index() {
        $withdrawals = Withdrawal::where('user_id',Auth::id())->orderByDesc('created_at')->paginate(20);
        $wallet = Auth::user()->wallet;
        return view('user.withdrawals', compact('withdrawals','wallet'));
    }
    public function create() {
        $wallet = Auth::user()->wallet;
        return view('user.withdrawal-create', compact('wallet'));
    }
    public function store(Request $request) {
        $request->validate(['amount'=>['required','numeric','min:5'],'method'=>['required','in:mobile_money,bank_transfer,paypal,usdt'],'payment_details'=>['required','array']]);
        $result = $this->walletService->processWithdrawal(Auth::user(),$request->amount,$request->method,$request->payment_details);
        if ($result['success']) return redirect()->route('withdrawal.index')->with('success',$result['message']);
        return back()->withErrors(['amount'=>$result['message']]);
    }
    public function show(Withdrawal $withdrawal) {
        abort_if($withdrawal->user_id !== Auth::id(),403);
        return view('user.withdrawal-show', compact('withdrawal'));
    }
}
