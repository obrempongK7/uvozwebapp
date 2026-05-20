<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VoicePost extends Model {
    use SoftDeletes;
    protected $fillable = ['user_id','title','description','audio_file','audio_format','duration',
        'file_size','cover_image','hashtags','mentions','visibility','status','plays_count',
        'likes_count','comments_count','reposts_count','shares_count','comments_enabled',
        'is_featured','country','engagement_score','published_at'];
    protected $casts = ['hashtags'=>'array','mentions'=>'array','published_at'=>'datetime',
        'comments_enabled'=>'boolean','is_featured'=>'boolean'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function comments(): MorphMany { return $this->morphMany(Comment::class,'commentable'); }
    public function reactions(): MorphMany { return $this->morphMany(Reaction::class,'reactable'); }
    public function getAudioUrlAttribute(): string { return asset('storage/'.$this->audio_file); }
    public function getCoverUrlAttribute(): ?string { return $this->cover_image ? asset('storage/'.$this->cover_image) : null; }
    public function getFormattedDurationAttribute(): string {
        $m = intdiv($this->duration,60); $s = $this->duration%60;
        return sprintf('%d:%02d',$m,$s);
    }
}
