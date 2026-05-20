<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model {
    protected $fillable = ['reporter_id','reportable_id','reportable_type','reason','details','status','reviewed_by','admin_notes'];
    public function reporter(): BelongsTo { return $this->belongsTo(User::class,'reporter_id'); }
    public function reportable(): MorphTo { return $this->morphTo(); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class,'reviewed_by'); }
}
