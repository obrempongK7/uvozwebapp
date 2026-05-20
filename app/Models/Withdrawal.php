<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model {
    protected $fillable = ['user_id','wallet_id','amount','currency','method','payment_details','status',
        'transaction_reference','approved_by','admin_notes','fee','net_amount','processed_at'];
    protected $casts = ['payment_details'=>'array','amount'=>'decimal:2','fee'=>'decimal:2','net_amount'=>'decimal:2','processed_at'=>'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function wallet(): BelongsTo { return $this->belongsTo(Wallet::class); }
}
