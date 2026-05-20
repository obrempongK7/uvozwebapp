<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DeviceSession extends Model {
    protected $fillable = ['user_id','device_id','device_name','device_type','browser','os','ip_address','location','last_active_at','is_current'];
    protected $casts = ['last_active_at'=>'datetime','is_current'=>'boolean'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
