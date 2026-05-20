<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
class Repost extends Model {
    protected $fillable = ['user_id','repostable_id','repostable_type','quote'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function repostable(): MorphTo { return $this->morphTo(); }
}
