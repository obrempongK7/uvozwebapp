<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatusPost extends Model {
    use SoftDeletes;
    protected $fillable = ['user_id','type','content','media_file','link_url','link_title','link_image',
        'contact_buttons','is_monetized','view_price','expires_at','views_count','likes_count',
        'comments_count','contact_clicks','visibility','status','country'];
    protected $casts = ['contact_buttons'=>'array','is_monetized'=>'boolean','expires_at'=>'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function comments(): MorphMany { return $this->morphMany(Comment::class,'commentable'); }
    public function reactions(): MorphMany { return $this->morphMany(Reaction::class,'reactable'); }
    public function isExpired(): bool { return $this->expires_at && $this->expires_at->isPast(); }
}
