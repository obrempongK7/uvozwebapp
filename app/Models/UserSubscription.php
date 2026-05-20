<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model {
    protected $fillable = ['user_id','plan_id','billing_cycle','payment_gateway','gateway_subscription_id',
        'gateway_customer_id','amount','currency','status','trial_ends_at','current_period_start',
        'current_period_end','cancelled_at','ends_at'];
    protected $casts = ['amount'=>'decimal:2','trial_ends_at'=>'datetime','current_period_start'=>'datetime',
        'current_period_end'=>'datetime','cancelled_at'=>'datetime','ends_at'=>'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function plan(): BelongsTo { return $this->belongsTo(SubscriptionPlan::class,'plan_id'); }
    public function isActive(): bool { return $this->status === 'active'; }
}
