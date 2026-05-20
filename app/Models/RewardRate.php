<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class RewardRate extends Model {
    protected $fillable = ['action_type','points_value','country','daily_limit','is_active'];
    protected $casts = ['is_active'=>'boolean'];
}
