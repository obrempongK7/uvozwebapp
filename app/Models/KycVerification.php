<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycVerification extends Model {
    protected $fillable = ['user_id','document_type','document_front','document_back','selfie_image',
        'document_number','document_expiry','status','reviewed_by','rejection_reason','reviewed_at'];
    protected $casts = ['document_expiry'=>'date','reviewed_at'=>'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class,'reviewed_by'); }
}
