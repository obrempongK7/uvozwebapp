<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\RewardRate;
use App\Models\FraudFlag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletService
{
    const POINTS_TO_USD = 1000; // 1000 points = $1

    public function getOrCreate(User $user): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['points_balance' => 0, 'pending_points' => 0, 'usd_balance' => 0,
             'pending_usd' => 0, 'today_points_earned' => 0, 'today_date' => now()->toDateString()]
        );
    }

    public function awardPoints(User $user, int $points, string $type, string $description, $source = null): bool
    {
        try {
            $wallet = $this->getOrCreate($user);

            // Reset daily counter if new day
            if ($wallet->today_date && $wallet->today_date->toDateString() !== now()->toDateString()) {
                $wallet->today_points_earned = 0;
                $wallet->today_date = now()->toDateString();
            }

            // Check daily cap
            $cap = $user->dailyPointsCap();
            if ($wallet->today_points_earned >= $cap) {
                return false;
            }

            // Apply earning boost
            $boost = $user->earningBoostPercent();
            if ($boost > 0) {
                $points = (int) round($points * (1 + $boost / 100));
            }

            // Limit to remaining cap
            $remaining = $cap - $wallet->today_points_earned;
            $points = min($points, $remaining);

            if ($points <= 0) return false;

            DB::transaction(function () use ($wallet, $user, $points, $type, $description, $source) {
                $wallet->increment('points_balance', $points);
                $wallet->increment('today_points_earned', $points);

                // Convert points to USD
                $usd = round($points / self::POINTS_TO_USD, 6);
                $wallet->increment('usd_balance', $usd);
                $wallet->increment('total_earned_usd', $usd);

                WalletTransaction::create([
                    'wallet_id'    => $wallet->id,
                    'user_id'      => $user->id,
                    'type'         => $type,
                    'points_amount'=> $points,
                    'usd_amount'   => $usd,
                    'description'  => $description,
                    'reference'    => Str::uuid(),
                    'source_id'    => $source?->id,
                    'source_type'  => $source ? get_class($source) : null,
                    'status'       => 'completed',
                ]);
            });

            // Award referral commission
            $this->awardReferralCommission($user, $points);

            return true;
        } catch (\Throwable $e) {
            Log::error('WalletService::awardPoints error', ['user' => $user->id, 'error' => $e->getMessage()]);
            return false;
        }
    }

    public function deductPoints(User $user, int $points, string $description, $source = null): bool
    {
        $wallet = $this->getOrCreate($user);
        if ($wallet->points_balance < $points) return false;

        DB::transaction(function () use ($wallet, $user, $points, $description, $source) {
            $wallet->decrement('points_balance', $points);
            $usd = round($points / self::POINTS_TO_USD, 6);
            $wallet->decrement('usd_balance', $usd);

            WalletTransaction::create([
                'wallet_id'    => $wallet->id,
                'user_id'      => $user->id,
                'type'         => 'points_deducted',
                'points_amount'=> $points,
                'usd_amount'   => $usd,
                'description'  => $description,
                'reference'    => Str::uuid(),
                'source_id'    => $source?->id,
                'source_type'  => $source ? get_class($source) : null,
                'status'       => 'completed',
            ]);
        });

        return true;
    }

    public function processWithdrawal(User $user, float $amount, string $method, array $paymentDetails): array
    {
        $wallet = $this->getOrCreate($user);

        if ($amount < 5) {
            return ['success' => false, 'message' => 'Minimum withdrawal amount is $5.00'];
        }

        if ($wallet->usd_balance < $amount) {
            return ['success' => false, 'message' => 'Insufficient balance'];
        }

        DB::transaction(function () use ($wallet, $user, $amount, $method, $paymentDetails) {
            $wallet->decrement('usd_balance', $amount);
            $wallet->increment('total_withdrawn_usd', $amount);

            // Deduct corresponding points
            $points = (int) round($amount * self::POINTS_TO_USD);
            $wallet->decrement('points_balance', min($points, $wallet->points_balance));

            \App\Models\Withdrawal::create([
                'user_id'         => $user->id,
                'wallet_id'       => $wallet->id,
                'amount'          => $amount,
                'method'          => $method,
                'payment_details' => $paymentDetails,
                'status'          => 'pending',
            ]);

            WalletTransaction::create([
                'wallet_id'    => $wallet->id,
                'user_id'      => $user->id,
                'type'         => 'withdrawal',
                'usd_amount'   => $amount,
                'description'  => 'Withdrawal request via ' . $method,
                'reference'    => Str::uuid(),
                'status'       => 'pending',
            ]);
        });

        return ['success' => true, 'message' => 'Withdrawal request submitted for review'];
    }

    private function awardReferralCommission(User $user, int $points): void
    {
        $referral = \App\Models\Referral::where('referred_id', $user->id)
                                        ->where('is_active', true)->first();
        if (!$referral) return;

        $commission = (int) round($points * $referral->commission_rate / 100);
        if ($commission <= 0) return;

        $referrer = $referral->referrer;
        if (!$referrer) return;

        $this->awardPoints($referrer, $commission, 'referral_bonus',
            "Referral commission from {$user->username}", $referral);

        $referral->increment('total_points_earned', $commission);
    }

    public function getRewardRate(string $action, ?string $country = null): int
    {
        $rate = \App\Models\RewardRate::where('action_type', $action)
            ->where(function ($q) use ($country) {
                $q->where('country', $country)->orWhereNull('country');
            })
            ->orderByRaw('country IS NULL ASC')
            ->where('is_active', true)
            ->first();

        return $rate?->points_value ?? 0;
    }

    public function pointsToUsd(int $points): float
    {
        return round($points / self::POINTS_TO_USD, 2);
    }

    public function usdToPoints(float $usd): int
    {
        return (int) round($usd * self::POINTS_TO_USD);
    }
}
