<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RewardCatalog extends Model {
    protected $fillable = ['title','description','image','category','points_required','usd_value',
        'stock_quantity','delivery_method','available_countries','processing_days','terms','is_active','redemptions_count'];
    protected $casts = ['available_countries'=>'array','is_active'=>'boolean','usd_value'=>'decimal:2'];
    public function redemptions(): HasMany { return $this->hasMany(RewardRedemption::class,'reward_id'); }
    public function getImageUrlAttribute(): string {
        return $this->image ? asset('storage/'.$this->image) : asset('images/default-reward.png');
    }
    public function isAvailableForCountry(string $country): bool {
        return !$this->available_countries || in_array($country, $this->available_countries);
    }
}
