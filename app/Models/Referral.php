<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model {
    protected $fillable = ['referrer_id','referred_id','commission_rate','total_points_earned','is_active'];
    protected $casts = ['commission_rate'=>'decimal:2','is_active'=>'boolean'];
    public function referrer(): BelongsTo { return $this->belongsTo(User::class,'referrer_id'); }
    public function referred(): BelongsTo { return $this->belongsTo(User::class,'referred_id'); }
}
