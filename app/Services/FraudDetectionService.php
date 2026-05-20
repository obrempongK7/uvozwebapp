<?php
namespace App\Services;
use App\Models\User;
use App\Models\FraudFlag;
use Illuminate\Support\Facades\DB;

class FraudDetectionService {
    public function checkAndFlag(User $user, string $action, array $context = []): bool {
        $suspicious = false;
        $reasons = [];
        $ip = $context['ip'] ?? request()->ip();
        $fp = $context['fingerprint'] ?? null;

        // Check for duplicate accounts by IP
        $ipAccounts = User::where('id','!=',$user->id)
            ->whereHas('auditLogs', fn($q) => $q->where('ip_address',$ip)->where('action','login'))
            ->count();
        if ($ipAccounts > 3) { $suspicious = true; $reasons[] = "Multiple accounts from IP $ip"; }

        // Check rapid point accumulation
        $recentPoints = \App\Models\WalletTransaction::where('user_id',$user->id)
            ->where('type','like','%earned%')
            ->where('created_at','>=',now()->subHour())
            ->sum('points_amount');
        if ($recentPoints > 5000) { $suspicious = true; $reasons[] = "Rapid point accumulation: $recentPoints in 1hr"; }

        // Check device fingerprint reuse
        if ($fp) {
            $fpUsers = User::where('id','!=',$user->id)
                ->whereHas('deviceSessions', fn($q) => $q->where('device_id',$fp))
                ->count();
            if ($fpUsers > 0) { $suspicious = true; $reasons[] = "Device fingerprint shared with other accounts"; }
        }

        if ($suspicious) {
            FraudFlag::create([
                'user_id' => $user->id,
                'reason' => implode('; ', $reasons),
                'ip_address' => $ip,
                'device_fingerprint' => $fp,
                'severity' => count($reasons) > 1 ? 'high' : 'medium',
                'status' => 'open',
                'evidence' => $context,
            ]);
        }
        return $suspicious;
    }
}
