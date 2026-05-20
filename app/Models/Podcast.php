<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Podcast extends Model {
    use SoftDeletes;
    protected $fillable = ['user_id','title','description','cover_artwork','category','tags','type',
        'price','status','is_explicit','language','country','rss_feed_url','subscribers_count','episodes_count'];
    protected $casts = ['tags'=>'array','is_explicit'=>'boolean','price'=>'decimal:2'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function episodes(): HasMany { return $this->hasMany(PodcastEpisode::class); }
    public function subscribers(): HasMany { return $this->hasMany(PodcastSubscriber::class); }
    public function getCoverUrlAttribute(): string {
        return $this->cover_artwork ? asset('storage/'.$this->cover_artwork) : asset('images/default-podcast.png');
    }
}
