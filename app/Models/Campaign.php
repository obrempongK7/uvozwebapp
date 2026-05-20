<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model {
    use SoftDeletes;
    protected $fillable = ['advertiser_id','title','description','cover_image','type','target_url',
        'target_countries','target_demographics','budget','spent','points_per_action','max_actions',
        'completed_actions','status','reviewed_by','rejection_reason','starts_at','ends_at'];
    protected $casts = ['target_countries'=>'array','target_demographics'=>'array','budget'=>'decimal:2',
        'spent'=>'decimal:2','starts_at'=>'datetime','ends_at'=>'datetime'];
    public function advertiser(): BelongsTo { return $this->belongsTo(User::class,'advertiser_id'); }
    public function interactions(): HasMany { return $this->hasMany(CampaignInteraction::class); }
    public function getRemainingBudgetAttribute(): float { return max(0, $this->budget - $this->spent); }
    public function isActive(): bool { return $this->status === 'active'; }
}
