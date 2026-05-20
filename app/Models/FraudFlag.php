<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FraudFlag extends Model {
    protected $fillable = ['user_id','reason','ip_address','device_fingerprint','severity','status','reviewed_by','evidence'];
    protected $casts = ['evidence'=>'array'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class,'reviewed_by'); }
}
