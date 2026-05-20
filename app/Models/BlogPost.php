<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model {
    use SoftDeletes;
    protected $fillable = ['author_id','title','slug','excerpt','content','featured_image','tags',
        'category','meta_title','meta_description','status','views_count','published_at','scheduled_at'];
    protected $casts = ['tags'=>'array','published_at'=>'datetime','scheduled_at'=>'datetime'];
    public function author(): BelongsTo { return $this->belongsTo(User::class,'author_id'); }
    public function getFeaturedImageUrlAttribute(): ?string {
        return $this->featured_image ? asset('storage/'.$this->featured_image) : null;
    }
}
