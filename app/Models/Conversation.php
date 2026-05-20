<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversation extends Model {
    protected $fillable = ['type','name','last_message_id','last_message_at'];
    protected $casts = ['last_message_at' => 'datetime'];
    public function messages(): HasMany { return $this->hasMany(Message::class); }
    public function participants(): BelongsToMany {
        return $this->belongsToMany(User::class,'conversation_participants')
                    ->withPivot(['last_read_at','is_muted','is_archived'])->withTimestamps();
    }
    public function lastMessage(): BelongsTo { return $this->belongsTo(Message::class,'last_message_id'); }
}
