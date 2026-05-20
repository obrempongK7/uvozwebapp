<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewardRedemption extends Model {
    protected $fillable = ['user_id','reward_id','wallet_id','points_spent','usd_value','status',
        'processed_by','admin_notes','delivery_details','tracking_number','fulfilled_at'];
    protected $casts = ['delivery_details'=>'array','usd_value'=>'decimal:2','fulfilled_at'=>'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function reward(): BelongsTo { return $this->belongsTo(RewardCatalog::class,'reward_id'); }
    public function wallet(): BelongsTo { return $this->belongsTo(Wallet::class); }
}
