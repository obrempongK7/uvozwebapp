<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignInteraction extends Model {
    protected $fillable = ['campaign_id','user_id','status','points_earned','ip_address','device_fingerprint','proof_data'];
    protected $casts = ['proof_data'=>'array'];
    public function campaign(): BelongsTo { return $this->belongsTo(Campaign::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
