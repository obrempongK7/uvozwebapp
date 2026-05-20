<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveRoomMessage extends Model {
    public $timestamps = false;
    protected $fillable = ['room_id','user_id','type','content','gift_type','gift_amount','created_at'];
    protected $casts = ['created_at'=>'datetime'];
    public function room(): BelongsTo { return $this->belongsTo(LiveRoom::class,'room_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
