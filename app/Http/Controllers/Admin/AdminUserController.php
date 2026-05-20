<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    public function index(Request $request)
    {
        $users = User::with(['profile','wallet','subscription.plan'])
            ->when($request->search, fn($q) =>
                $q->where('username','like',"%{$request->search}%")
                  ->orWhere('email','like',"%{$request->search}%")
            )
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->status === 'banned', fn($q) => $q->where('is_banned', true))
            ->when($request->status === 'active', fn($q) => $q->where('is_active', true)->where('is_banned', false))
            ->when($request->verified, fn($q) => $q->where('is_verified', true))
            ->orderByDesc('created_at')
            ->paginate(25);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['profile','wallet','subscription.plan','referrals']);
        $recentActivity = AuditLog::where('user_id', $user->id)->orderByDesc('created_at')->limit(20)->get();
        $transactions   = $user->walletTransactions()->orderByDesc('created_at')->limit(20)->get();
        $withdrawals    = \App\Models\Withdrawal::where('user_id', $user->id)->orderByDesc('created_at')->limit(10)->get();

        return view('admin.users.show', compact('user','recentActivity','transactions','withdrawals'));
    }

    public function verify(User $user)
    {
        $user->update(['is_verified' => !$user->is_verified]);
        $this->auditLog('user_verified', $user);
        return back()->with('success', 'Verification status updated.');
    }

    public function ban(Request $request, User $user)
    {
        $request->validate(['reason' => ['required','string','max:500']]);
        $user->update(['is_banned' => true, 'ban_reason' => $request->reason]);
        $this->auditLog('user_banned', $user, ['reason' => $request->reason]);
        return back()->with('success', 'User banned.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false, 'ban_reason' => null]);
        $this->auditLog('user_unbanned', $user);
        return back()->with('success', 'User unbanned.');
    }

    public function adjustWallet(Request $request, User $user)
    {
        $request->validate([
            'type'        => ['required','in:add,deduct'],
            'points'      => ['required','integer','min:1'],
            'description' => ['required','string'],
        ]);

        if ($request->type === 'add') {
            $this->walletService->awardPoints($user, $request->points, 'admin_adjustment', $request->description);
        } else {
            $this->walletService->deductPoints($user, $request->points, $request->description);
        }

        $this->auditLog('wallet_adjusted', $user, $request->only('type','points','description'));
        return back()->with('success', 'Wallet adjusted.');
    }

    public function impersonate(User $user)
    {
        session(['impersonate_admin' => Auth::id()]);
        Auth::login($user);
        $this->auditLog('impersonated', $user);
        return redirect()->route('dashboard')->with('warning', 'You are impersonating ' . $user->username);
    }

    public function destroy(User $user)
    {
        $user->delete();
        $this->auditLog('user_deleted', $user);
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    private function auditLog(string $action, User $target, array $data = []): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'model_type' => User::class,
            'model_id'   => $target->id,
            'new_values' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
