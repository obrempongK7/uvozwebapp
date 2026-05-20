<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model {
    protected $fillable = ['name','slug','description','monthly_price','yearly_price','daily_points_cap',
        'earning_boost_percent','ads_enabled','priority_support','faster_withdrawals','features','is_active','sort_order'];
    protected $casts = ['features'=>'array','monthly_price'=>'decimal:2','yearly_price'=>'decimal:2',
        'ads_enabled'=>'boolean','priority_support'=>'boolean','faster_withdrawals'=>'boolean','is_active'=>'boolean'];
    public function subscriptions(): HasMany { return $this->hasMany(UserSubscription::class,'plan_id'); }
    public function isFree(): bool { return $this->monthly_price == 0; }
}
