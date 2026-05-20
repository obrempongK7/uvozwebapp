<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
class PostView extends Model {
    public $timestamps = false;
    protected $fillable = ['user_id','viewable_id','viewable_type','ip_address','device_fingerprint','created_at'];
    protected $casts = ['created_at'=>'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function viewable(): MorphTo { return $this->morphTo(); }
}
