<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model {
    protected $fillable = ['user_id','points_balance','pending_points','usd_balance','pending_usd',
        'total_earned_usd','total_withdrawn_usd','today_points_earned','today_date'];
    protected $casts = ['today_date'=>'date','usd_balance'=>'decimal:2','usd_balance'=>'decimal:2'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function transactions(): HasMany { return $this->hasMany(WalletTransaction::class); }
    public function getUsdEquivalentAttribute(): float { return round($this->points_balance / 1000, 2); }
}
