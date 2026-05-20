<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveRoomParticipant extends Model {
    protected $fillable = ['room_id','user_id','role','is_muted','has_raised_hand','is_active','joined_at','left_at'];
    protected $casts = ['is_muted'=>'boolean','has_raised_hand'=>'boolean','is_active'=>'boolean','joined_at'=>'datetime','left_at'=>'datetime'];
    public function room(): BelongsTo { return $this->belongsTo(LiveRoom::class,'room_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
