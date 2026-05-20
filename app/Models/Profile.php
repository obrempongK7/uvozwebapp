<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model {
    protected $fillable = ['user_id','location','birth_date','gender','language','interests',
        'followers_count','following_count','posts_count','voice_posts_count','podcast_episodes_count',
        'profile_public','allow_messages','show_earnings'];
    protected $casts = ['interests'=>'array','profile_public'=>'boolean','allow_messages'=>'boolean','show_earnings'=>'boolean'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
