<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model {
    protected $fillable = ['key','value','group','type','is_public'];
    protected $casts = ['is_public'=>'boolean'];

    public static function get(string $key, mixed $default = null): mixed {
        return Cache::rememberForever("setting_{$key}", function() use ($key, $default) {
            $s = static::where('key',$key)->first();
            if (!$s) return $default;
            return match($s->type) {
                'boolean' => (bool) $s->value,
                'integer' => (int) $s->value,
                'json'    => json_decode($s->value, true),
                default   => $s->value,
            };
        });
    }

    public static function set(string $key, mixed $value, string $group='general', string $type='string'): void {
        $stored = is_array($value) ? json_encode($value) : $value;
        static::updateOrCreate(['key'=>$key], ['value'=>$stored,'group'=>$group,'type'=>$type]);
        Cache::forget("setting_{$key}");
    }
}
