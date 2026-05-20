<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PodcastSubscriber extends Model {
    protected $fillable = ['podcast_id','user_id','type','expires_at'];
    protected $casts = ['expires_at'=>'datetime'];
    public function podcast(): BelongsTo { return $this->belongsTo(Podcast::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
