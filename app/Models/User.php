<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username','email','phone','password','display_name','bio',
        'avatar','cover_image','country','website','social_links',
        'is_verified','is_active','is_banned','ban_reason','role',
        'referral_code','referred_by','two_factor_enabled','last_seen_at',
    ];

    protected $hidden = ['password','remember_token','two_factor_secret','two_factor_recovery_codes'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'last_seen_at'      => 'datetime',
        'social_links'      => 'array',
        'is_verified'       => 'boolean',
        'is_active'         => 'boolean',
        'is_banned'         => 'boolean',
        'two_factor_enabled'=> 'boolean',
    ];

    // ---------- Relationships ----------

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function voicePosts(): HasMany
    {
        return $this->hasMany(VoicePost::class);
    }

    public function statusPosts(): HasMany
    {
        return $this->hasMany(StatusPost::class);
    }

    public function podcasts(): HasMany
    {
        return $this->hasMany(Podcast::class);
    }

    public function liveRooms(): HasMany
    {
        return $this->hasMany(LiveRoom::class, 'host_id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id')
                    ->withTimestamps();
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id')
                    ->withTimestamps();
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(UserSubscription::class)->where('status', 'active')->latestOfMany();
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable_id')
                    ->where('notifiable_type', self::class);
    }

    // ---------- Helpers ----------

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->display_name ?? $this->username) . '&background=7C3AED&color=fff&size=200';
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    public function getDisplayNameAttribute($value): string
    {
        return $value ?? $this->username;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isModerator(): bool
    {
        return in_array($this->role, ['moderator', 'admin', 'super_admin']);
    }

    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_id', $user->id)->exists();
    }

    public function dailyPointsCap(): int
    {
        $sub = $this->subscription;
        if ($sub && $sub->plan) {
            return $sub->plan->daily_points_cap;
        }
        return 1000; // free plan default
    }

    public function earningBoostPercent(): int
    {
        $sub = $this->subscription;
        if ($sub && $sub->plan) {
            return $sub->plan->earning_boost_percent;
        }
        return 0;
    }

    public function auditLogs(): HasMany { return $this->hasMany(\App\Models\AuditLog::class); }
    public function deviceSessions(): HasMany { return $this->hasMany(\App\Models\DeviceSession::class); }
}
