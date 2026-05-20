<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PodcastEpisode extends Model {
    use SoftDeletes;
    protected $fillable = ['podcast_id','user_id','title','show_notes','audio_file','duration','file_size',
        'episode_number','season_number','type','tags','access','price','status','is_explicit',
        'plays_count','downloads_count','likes_count','comments_count','published_at','scheduled_at'];
    protected $casts = ['tags'=>'array','is_explicit'=>'boolean','published_at'=>'datetime','scheduled_at'=>'datetime','price'=>'decimal:2'];
    public function podcast(): BelongsTo { return $this->belongsTo(Podcast::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function getAudioUrlAttribute(): string { return asset('storage/'.$this->audio_file); }
    public function getFormattedDurationAttribute(): string {
        $m = intdiv($this->duration,60); $s = $this->duration%60;
        return sprintf('%d:%02d',$m,$s);
    }
}
