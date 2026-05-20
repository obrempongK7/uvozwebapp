<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends Model {
    protected $fillable = ['wallet_id','user_id','type','points_amount','usd_amount','description',
        'reference','source_id','source_type','status','meta'];
    protected $casts = ['meta'=>'array','usd_amount'=>'decimal:2'];
    public function wallet(): BelongsTo { return $this->belongsTo(Wallet::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function source(): MorphTo { return $this->morphTo(); }
}
