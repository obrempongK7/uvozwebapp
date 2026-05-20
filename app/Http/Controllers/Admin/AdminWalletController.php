<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\RewardCatalog;
use App\Models\RewardRedemption;
use App\Models\RewardRate;
use App\Models\WalletTransaction;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminWalletController extends Controller
{
    public function __construct(private WalletService $walletService) {}

    public function withdrawals(Request $request)
    {
        $withdrawals = Withdrawal::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->method, fn($q) => $q->where('method', $request->method))
            ->orderByDesc('created_at')
            ->paginate(25);

        $totals = [
            'pending'   => Withdrawal::where('status','pending')->sum('amount'),
            'completed' => Withdrawal::where('status','completed')->sum('amount'),
        ];

        return view('admin.wallet.withdrawals', compact('withdrawals','totals'));
    }

    public function approveWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Only pending withdrawals can be approved.');
        }

        $withdrawal->update([
            'status'                => 'approved',
            'approved_by'           => Auth::id(),
            'transaction_reference' => $request->reference ?? null,
            'admin_notes'           => $request->notes ?? null,
            'processed_at'          => now(),
            'net_amount'            => $withdrawal->amount - $withdrawal->fee,
        ]);

        // Notify user
        $withdrawal->user->notify(new \App\Notifications\GeneralNotification(
            'withdrawal',
            'Withdrawal Approved',
            "Your withdrawal of \${$withdrawal->amount} has been approved and is being processed.",
            [],
            route('withdrawal.show', $withdrawal)
        ));

        return back()->with('success', 'Withdrawal approved.');
    }

    public function rejectWithdrawal(Request $request, Withdrawal $withdrawal)
    {
        $request->validate(['reason' => ['required','string']]);

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Only pending withdrawals can be rejected.');
        }

        // Refund balance
        $wallet = $withdrawal->wallet;
        $wallet->increment('usd_balance', $withdrawal->amount);
        $wallet->decrement('total_withdrawn_usd', $withdrawal->amount);

        $withdrawal->update([
            'status'      => 'rejected',
            'admin_notes' => $request->reason,
        ]);

        WalletTransaction::create([
            'wallet_id'    => $wallet->id,
            'user_id'      => $withdrawal->user_id,
            'type'         => 'admin_adjustment',
            'usd_amount'   => $withdrawal->amount,
            'description'  => 'Withdrawal rejected - funds returned: ' . $request->reason,
            'reference'    => \Str::uuid(),
            'status'       => 'completed',
        ]);

        return back()->with('success', 'Withdrawal rejected and funds returned.');
    }

    public function redemptions(Request $request)
    {
        $redemptions = RewardRedemption::with(['user','reward'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')->paginate(25);

        return view('admin.wallet.redemptions', compact('redemptions'));
    }

    public function fulfillRedemption(Request $request, RewardRedemption $redemption)
    {
        $redemption->update([
            'status'          => 'fulfilled',
            'processed_by'    => Auth::id(),
            'admin_notes'     => $request->notes,
            'tracking_number' => $request->tracking_number,
            'delivery_details'=> $request->delivery_details ? json_decode($request->delivery_details, true) : null,
            'fulfilled_at'    => now(),
        ]);

        $redemption->reward->increment('redemptions_count');

        return back()->with('success', 'Redemption fulfilled.');
    }

    public function rewardCatalog()
    {
        $rewards = RewardCatalog::orderByDesc('created_at')->paginate(20);
        return view('admin.wallet.catalog', compact('rewards'));
    }

    public function storeReward(Request $request)
    {
        $request->validate([
            'title'             => ['required','string','max:200'],
            'category'          => ['required','string'],
            'points_required'   => ['required','integer','min:1'],
            'delivery_method'   => ['required','in:digital,physical,automatic'],
        ]);

        $data = $request->except('_token','image');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rewards','public');
        }
        $data['available_countries'] = $request->available_countries
            ? explode(',', $request->available_countries) : null;

        RewardCatalog::create($data);
        return back()->with('success', 'Reward created.');
    }

    public function updateReward(Request $request, RewardCatalog $reward)
    {
        $data = $request->except('_token','_method','image');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rewards','public');
        }
        $reward->update($data);
        return back()->with('success', 'Reward updated.');
    }

    public function deleteReward(RewardCatalog $reward)
    {
        $reward->delete();
        return back()->with('success', 'Reward deleted.');
    }

    public function rewardRates()
    {
        $rates = RewardRate::orderBy('action_type')->get();
        return view('admin.wallet.rates', compact('rates'));
    }

    public function updateRates(Request $request)
    {
        foreach ($request->rates as $id => $data) {
            RewardRate::where('id', $id)->update([
                'points_value' => $data['points_value'],
                'daily_limit'  => $data['daily_limit'] ?? null,
                'is_active'    => isset($data['is_active']),
            ]);
        }
        return back()->with('success', 'Reward rates updated.');
    }
}
