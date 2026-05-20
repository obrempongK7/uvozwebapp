<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Hashtag extends Model {
    protected $fillable = ['name','posts_count','is_trending'];
    protected $casts = ['is_trending'=>'boolean'];
}
