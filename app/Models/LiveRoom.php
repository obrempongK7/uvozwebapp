<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiveRoom extends Model {
    use SoftDeletes;
    protected $fillable = ['host_id','title','description','cover_image','type','password','status',
        'is_recorded','recording_file','is_ticketed','ticket_price','max_participants','listeners_count',
        'peak_listeners','room_token','country','tags','scheduled_at','started_at','ended_at'];
    protected $casts = ['tags'=>'array','is_recorded'=>'boolean','is_ticketed'=>'boolean',
        'ticket_price'=>'decimal:2','scheduled_at'=>'datetime','started_at'=>'datetime','ended_at'=>'datetime'];
    public function host(): BelongsTo { return $this->belongsTo(User::class,'host_id'); }
    public function participants(): HasMany { return $this->hasMany(LiveRoomParticipant::class,'room_id'); }
    public function messages(): HasMany { return $this->hasMany(LiveRoomMessage::class,'room_id'); }
    public function isLive(): bool { return $this->status === 'live'; }
}
