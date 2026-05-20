<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Wallet;
use App\Models\SubscriptionPlan;
use App\Models\RewardRate;
use App\Models\SiteSetting;
use App\Models\Badge;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            SubscriptionPlanSeeder::class,
            RewardRateSeeder::class,
            BadgeSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name',        'value' => 'UVOZ',                          'group' => 'general',  'type' => 'string',  'is_public' => true],
            ['key' => 'site_tagline',     'value' => 'Speak. Connect. Earn.',          'group' => 'general',  'type' => 'string',  'is_public' => true],
            ['key' => 'site_description', 'value' => 'Voice-first social network where you earn from authentic engagement.', 'group' => 'general', 'type' => 'string', 'is_public' => true],
            ['key' => 'site_email',       'value' => 'hello@uvoz.app',                'group' => 'general',  'type' => 'string',  'is_public' => true],
            ['key' => 'site_url',         'value' => 'https://uvoz.app',              'group' => 'general',  'type' => 'string',  'is_public' => true],
            ['key' => 'points_per_usd',   'value' => '1000',                          'group' => 'rewards',  'type' => 'integer', 'is_public' => true],
            ['key' => 'min_withdrawal',   'value' => '5',                             'group' => 'rewards',  'type' => 'integer', 'is_public' => true],
            ['key' => 'referral_commission', 'value' => '10',                         'group' => 'rewards',  'type' => 'integer', 'is_public' => true],
            ['key' => 'maintenance_mode', 'value' => '0',                             'group' => 'general',  'type' => 'boolean', 'is_public' => false],
            ['key' => 'registration_open','value' => '1',                             'group' => 'general',  'type' => 'boolean', 'is_public' => true],
            ['key' => 'meta_title',       'value' => 'UVOZ - Speak. Connect. Earn.',  'group' => 'seo',      'type' => 'string',  'is_public' => true],
            ['key' => 'meta_description', 'value' => 'Share your voice, grow your audience, and earn real money on UVOZ.',  'group' => 'seo', 'type' => 'string', 'is_public' => true],
            ['key' => 'stripe_enabled',   'value' => '0',                             'group' => 'payment',  'type' => 'boolean', 'is_public' => false],
            ['key' => 'paystack_enabled', 'value' => '0',                             'group' => 'payment',  'type' => 'boolean', 'is_public' => false],
            ['key' => 'support_email',    'value' => 'support@uvoz.app',              'group' => 'general',  'type' => 'string',  'is_public' => true],
        ];

        foreach ($settings as $setting) {
            \App\Models\SiteSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name'                  => 'Free',
                'slug'                  => 'free',
                'description'           => 'Get started with UVOZ for free.',
                'monthly_price'         => 0,
                'yearly_price'          => 0,
                'daily_points_cap'      => 1000,
                'earning_boost_percent' => 0,
                'ads_enabled'           => true,
                'priority_support'      => false,
                'faster_withdrawals'    => false,
                'features'              => ['1000 pts/day cap', 'Ads enabled', 'Standard access'],
                'is_active'             => true,
                'sort_order'            => 1,
            ],
            [
                'name'                  => 'UVOZ Plus',
                'slug'                  => 'plus',
                'description'           => 'Boost your earnings and reduce ads.',
                'monthly_price'         => 3.00,
                'yearly_price'          => 29.00,
                'daily_points_cap'      => 2000,
                'earning_boost_percent' => 30,
                'ads_enabled'           => true,
                'priority_support'      => false,
                'faster_withdrawals'    => true,
                'features'              => ['2000 pts/day cap', '30% earning boost', 'Fewer ads', 'Faster withdrawals'],
                'is_active'             => true,
                'sort_order'            => 2,
            ],
            [
                'name'                  => 'UVOZ Pro',
                'slug'                  => 'pro',
                'description'           => 'Maximum earnings, zero ads, priority support.',
                'monthly_price'         => 7.00,
                'yearly_price'          => 65.00,
                'daily_points_cap'      => 5000,
                'earning_boost_percent' => 70,
                'ads_enabled'           => false,
                'priority_support'      => true,
                'faster_withdrawals'    => true,
                'features'              => ['5000 pts/day cap', '70% earning boost', 'Ad-free', 'Priority support', 'Fastest withdrawals'],
                'is_active'             => true,
                'sort_order'            => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}

class RewardRateSeeder extends Seeder
{
    public function run(): void
    {
        $rates = [
            ['action_type' => 'voice_post_published',   'points_value' => 50,  'daily_limit' => 5],
            ['action_type' => 'voice_play_received',     'points_value' => 5,   'daily_limit' => null],
            ['action_type' => 'voice_listen',            'points_value' => 2,   'daily_limit' => 200],
            ['action_type' => 'comment_posted',          'points_value' => 5,   'daily_limit' => 50],
            ['action_type' => 'status_view_received',    'points_value' => 3,   'daily_limit' => null],
            ['action_type' => 'contact_click',           'points_value' => 10,  'daily_limit' => null],
            ['action_type' => 'podcast_listen',          'points_value' => 3,   'daily_limit' => 100],
            ['action_type' => 'live_room_listen',        'points_value' => 5,   'daily_limit' => 50],
            ['action_type' => 'referral_signup',         'points_value' => 200, 'daily_limit' => null],
            ['action_type' => 'profile_complete',        'points_value' => 100, 'daily_limit' => 1],
            ['action_type' => 'daily_login',             'points_value' => 10,  'daily_limit' => 1],
        ];

        foreach ($rates as $rate) {
            RewardRate::updateOrCreate(
                ['action_type' => $rate['action_type'], 'country' => null],
                array_merge($rate, ['is_active' => true])
            );
        }
    }
}

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [
            ['name' => 'First Voice',      'slug' => 'first-voice',      'description' => 'Published your first voice post', 'icon' => '🎙️', 'color' => '#7C3AED', 'points_required' => 0],
            ['name' => 'Rising Star',      'slug' => 'rising-star',      'description' => 'Reached 100 followers',           'icon' => '⭐', 'color' => '#F59E0B', 'points_required' => 0],
            ['name' => 'Popular Creator',  'slug' => 'popular-creator',  'description' => 'Reached 1,000 followers',         'icon' => '🔥', 'color' => '#EC4899', 'points_required' => 0],
            ['name' => 'Super Earner',     'slug' => 'super-earner',     'description' => 'Earned 100,000 points',           'icon' => '💰', 'color' => '#10B981', 'points_required' => 100000],
            ['name' => 'Verified Creator', 'slug' => 'verified-creator', 'description' => 'Completed KYC verification',      'icon' => '✅', 'color' => '#3B82F6', 'points_required' => 0],
            ['name' => 'Podcast Host',     'slug' => 'podcast-host',     'description' => 'Created your first podcast',      'icon' => '🎧', 'color' => '#8B5CF6', 'points_required' => 0],
            ['name' => 'Streak Master',    'slug' => 'streak-master',    'description' => 'Maintained a 30-day streak',      'icon' => '📅', 'color' => '#EF4444', 'points_required' => 0],
            ['name' => 'Connector',        'slug' => 'connector',        'description' => 'Referred 10 friends',             'icon' => '🤝', 'color' => '#06B6D4', 'points_required' => 0],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(['slug' => $badge['slug']], $badge + ['is_active' => true]);
        }
    }
}

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@uvoz.app'],
            [
                'username'         => 'admin',
                'display_name'     => 'UVOZ Admin',
                'password'         => Hash::make('Admin@123456'),
                'role'             => 'super_admin',
                'is_verified'      => true,
                'is_active'        => true,
                'email_verified_at'=> now(),
                'referral_code'    => 'UVOZADMIN',
            ]
        );

        Profile::firstOrCreate(['user_id' => $admin->id], ['profile_public' => true]);
        Wallet::firstOrCreate(['user_id' => $admin->id], ['today_date' => now()->toDateString()]);

        // Demo user
        $demo = User::updateOrCreate(
            ['email' => 'demo@uvoz.app'],
            [
                'username'         => 'demouser',
                'display_name'     => 'Demo User',
                'password'         => Hash::make('Demo@123456'),
                'role'             => 'user',
                'is_verified'      => true,
                'is_active'        => true,
                'email_verified_at'=> now(),
                'referral_code'    => Str::upper(Str::random(8)),
                'country'          => 'US',
                'bio'              => 'This is a demo account for testing UVOZ features.',
            ]
        );

        Profile::firstOrCreate(['user_id' => $demo->id], ['profile_public' => true, 'followers_count' => 0]);
        Wallet::firstOrCreate(
            ['user_id' => $demo->id],
            ['points_balance' => 5000, 'usd_balance' => 5.00, 'today_date' => now()->toDateString()]
        );
    }
}
