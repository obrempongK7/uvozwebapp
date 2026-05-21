<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\VoiceController;
use App\Http\Controllers\User\StatusController;
use App\Http\Controllers\User\PodcastController;
use App\Http\Controllers\User\LiveRoomController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\WithdrawalController;
use App\Http\Controllers\User\CampaignController;
use App\Http\Controllers\User\MessagingController;
use App\Http\Controllers\User\NotificationController;
use App\Http\Controllers\User\SearchController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\FeedController;
use App\Http\Controllers\User\ReferralController;
use App\Http\Controllers\User\KycController;
use App\Http\Controllers\User\RewardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminContentController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\AdminCampaignController;
use App\Http\Controllers\Admin\AdminKycController;
use App\Http\Controllers\Admin\AdminSettingsController;
use App\Http\Controllers\Admin\AdminAnalyticsController;
use App\Http\Controllers\Admin\AdminBlogController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\BlogController;

// ==============================
// LANDING / PUBLIC
// ==============================
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/about', [LandingController::class, 'about'])->name('about');
Route::get('/how-it-works', [LandingController::class, 'howItWorks'])->name('how-it-works');
Route::get('/pricing', [LandingController::class, 'pricing'])->name('pricing');
Route::get('/features', [LandingController::class, 'features'])->name('features');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');
Route::get('/contact', [LandingController::class, 'contact'])->name('contact');
Route::post('/contact', [LandingController::class, 'sendContact'])->name('contact.send');
Route::get('/privacy-policy', [LandingController::class, 'privacy'])->name('privacy');
Route::get('/terms-of-service', [LandingController::class, 'terms'])->name('terms');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// ==============================
// AUTH
// ==============================
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Two-factor and OTP
Route::middleware('auth')->group(function () {
    Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('otp.verify');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');

    Route::get('/two-factor', [AuthController::class, 'showTwoFactor'])->name('two-factor');
    Route::post('/two-factor', [AuthController::class, 'verifyTwoFactor']);
});

// ==============================
// AUTHENTICATED USER ROUTES
// ==============================
Route::middleware(['auth', 'verified:otp.verify', 'active'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Feed
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
    Route::get('/feed/following', [FeedController::class, 'following'])->name('feed.following');
    Route::get('/feed/trending', [FeedController::class, 'trending'])->name('feed.trending');
    Route::get('/explore', [FeedController::class, 'explore'])->name('explore');

    // Profile
    Route::get('/profile', [ProfileController::class, 'myProfile'])->name('profile.me');
    Route::get('/profile/{username}', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::post('/profile/cover', [ProfileController::class, 'updateCover'])->name('profile.cover');
    Route::post('/follow/{user}', [ProfileController::class, 'follow'])->name('profile.follow');
    Route::delete('/follow/{user}', [ProfileController::class, 'unfollow'])->name('profile.unfollow');

    // Voice Hub
    Route::prefix('voice')->name('voice.')->group(function () {
        Route::get('/', [VoiceController::class, 'index'])->name('index');
        Route::get('/create', [VoiceController::class, 'create'])->name('create');
        Route::post('/', [VoiceController::class, 'store'])->name('store');
        Route::get('/{voicePost}', [VoiceController::class, 'show'])->name('show');
        Route::get('/{voicePost}/edit', [VoiceController::class, 'edit'])->name('edit');
        Route::put('/{voicePost}', [VoiceController::class, 'update'])->name('update');
        Route::delete('/{voicePost}', [VoiceController::class, 'destroy'])->name('destroy');
        Route::post('/{voicePost}/play', [VoiceController::class, 'recordPlay'])->name('play');
        Route::post('/{voicePost}/like', [VoiceController::class, 'like'])->name('like');
        Route::post('/{voicePost}/repost', [VoiceController::class, 'repost'])->name('repost');
        Route::post('/{voicePost}/comment', [VoiceController::class, 'comment'])->name('comment');
        Route::post('/{voicePost}/report', [VoiceController::class, 'report'])->name('report');
    });

    // Status Hub
    Route::prefix('status')->name('status.')->group(function () {
        Route::get('/', [StatusController::class, 'index'])->name('index');
        Route::post('/', [StatusController::class, 'store'])->name('store');
        Route::get('/{statusPost}', [StatusController::class, 'show'])->name('show');
        Route::delete('/{statusPost}', [StatusController::class, 'destroy'])->name('destroy');
        Route::post('/{statusPost}/view', [StatusController::class, 'recordView'])->name('view');
        Route::post('/{statusPost}/like', [StatusController::class, 'like'])->name('like');
        Route::post('/{statusPost}/contact-click', [StatusController::class, 'contactClick'])->name('contact-click');
        Route::post('/{statusPost}/report', [StatusController::class, 'report'])->name('report');
    });

    // Podcast Hub
    Route::prefix('podcasts')->name('podcast.')->group(function () {
        Route::get('/', [PodcastController::class, 'index'])->name('index');
        Route::get('/create', [PodcastController::class, 'create'])->name('create');
        Route::post('/', [PodcastController::class, 'store'])->name('store');
        Route::get('/{podcast}', [PodcastController::class, 'show'])->name('show');
        Route::get('/{podcast}/edit', [PodcastController::class, 'edit'])->name('edit');
        Route::put('/{podcast}', [PodcastController::class, 'update'])->name('update');
        Route::delete('/{podcast}', [PodcastController::class, 'destroy'])->name('destroy');
        Route::post('/{podcast}/subscribe', [PodcastController::class, 'subscribe'])->name('subscribe');
        Route::delete('/{podcast}/subscribe', [PodcastController::class, 'unsubscribe'])->name('unsubscribe');
        Route::post('/{podcast}/episodes', [PodcastController::class, 'storeEpisode'])->name('episodes.store');
        Route::get('/{podcast}/episodes/{episode}', [PodcastController::class, 'showEpisode'])->name('episodes.show');
        Route::post('/{podcast}/episodes/{episode}/play', [PodcastController::class, 'recordPlay'])->name('episodes.play');
        Route::delete('/{podcast}/episodes/{episode}', [PodcastController::class, 'destroyEpisode'])->name('episodes.destroy');
    });

    // Live Rooms
    Route::prefix('live')->name('live.')->group(function () {
        Route::get('/', [LiveRoomController::class, 'index'])->name('index');
        Route::get('/create', [LiveRoomController::class, 'create'])->name('create');
        Route::post('/', [LiveRoomController::class, 'store'])->name('store');
        Route::get('/{liveRoom}', [LiveRoomController::class, 'show'])->name('show');
        Route::post('/{liveRoom}/join', [LiveRoomController::class, 'join'])->name('join');
        Route::post('/{liveRoom}/leave', [LiveRoomController::class, 'leave'])->name('leave');
        Route::post('/{liveRoom}/start', [LiveRoomController::class, 'start'])->name('start');
        Route::post('/{liveRoom}/end', [LiveRoomController::class, 'end'])->name('end');
        Route::post('/{liveRoom}/message', [LiveRoomController::class, 'sendMessage'])->name('message');
        Route::post('/{liveRoom}/raise-hand', [LiveRoomController::class, 'raiseHand'])->name('raise-hand');
        Route::post('/{liveRoom}/invite-speaker/{user}', [LiveRoomController::class, 'inviteSpeaker'])->name('invite-speaker');
    });

    // Wallet
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
        Route::get('/rewards', [RewardController::class, 'index'])->name('rewards');
        Route::post('/rewards/{reward}/redeem', [RewardController::class, 'redeem'])->name('rewards.redeem');
    });

    // Withdrawals
    Route::prefix('withdrawals')->name('withdrawal.')->group(function () {
        Route::get('/', [WithdrawalController::class, 'index'])->name('index');
        Route::get('/create', [WithdrawalController::class, 'create'])->name('create');
        Route::post('/', [WithdrawalController::class, 'store'])->name('store');
        Route::get('/{withdrawal}', [WithdrawalController::class, 'show'])->name('show');
    });

    // Subscriptions
    Route::get('/subscribe', [WalletController::class, 'plans'])->name('subscribe.plans');
    Route::post('/subscribe/{plan}', [WalletController::class, 'subscribe'])->name('subscribe.store');
    Route::post('/subscribe/cancel', [WalletController::class, 'cancelSubscription'])->name('subscribe.cancel');
    Route::get('/subscribe/success', [WalletController::class, 'subscribeSuccess'])->name('subscribe.success');

    // Campaigns
    Route::prefix('campaigns')->name('campaign.')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('index');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::post('/', [CampaignController::class, 'store'])->name('store');
        Route::get('/{campaign}', [CampaignController::class, 'show'])->name('show');
        Route::post('/{campaign}/interact', [CampaignController::class, 'interact'])->name('interact');
        Route::get('/my/campaigns', [CampaignController::class, 'myCampaigns'])->name('mine');
        Route::get('/{campaign}/analytics', [CampaignController::class, 'analytics'])->name('analytics');
    });

    // Messaging
    Route::prefix('messages')->name('message.')->group(function () {
        Route::get('/', [MessagingController::class, 'index'])->name('index');
        Route::get('/c/{conversation}', [MessagingController::class, 'show'])->name('show');
        Route::post('/new/{user}', [MessagingController::class, 'newConversation'])->name('new');
        Route::post('/c/{conversation}/send', [MessagingController::class, 'send'])->name('send');
        Route::delete('/c/{conversation}', [MessagingController::class, 'archive'])->name('archive');
    });

    // Notifications
    Route::prefix('notifications')->name('notification.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
        Route::post('/read-all', [NotificationController::class, 'markAllRead'])->name('read-all');
    });

    // Search
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Referrals
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');

    // KYC
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', [KycController::class, 'index'])->name('index');
        Route::post('/submit', [KycController::class, 'submit'])->name('submit');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/account', [SettingsController::class, 'updateAccount'])->name('account');
        Route::post('/privacy', [SettingsController::class, 'updatePrivacy'])->name('privacy');
        Route::post('/notifications', [SettingsController::class, 'updateNotifications'])->name('notifications');
        Route::post('/password', [SettingsController::class, 'updatePassword'])->name('password');
        Route::post('/two-factor/enable', [SettingsController::class, 'enableTwoFactor'])->name('2fa.enable');
        Route::post('/two-factor/disable', [SettingsController::class, 'disableTwoFactor'])->name('2fa.disable');
        Route::get('/sessions', [SettingsController::class, 'sessions'])->name('sessions');
        Route::delete('/sessions/{id}', [SettingsController::class, 'revokeSession'])->name('sessions.revoke');
        Route::post('/rewards/preferences', [SettingsController::class, 'updateRewardPreferences'])->name('rewards');
        Route::post('/shipping-address', [SettingsController::class, 'updateShippingAddress'])->name('shipping');
        Route::delete('/account', [SettingsController::class, 'deleteAccount'])->name('delete');
    });
});

// ==============================
// ADMIN PANEL
// ==============================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AdminAnalyticsController::class, 'index'])->name('analytics');

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/{user}', [AdminUserController::class, 'show'])->name('show');
        Route::post('/{user}/verify', [AdminUserController::class, 'verify'])->name('verify');
        Route::post('/{user}/ban', [AdminUserController::class, 'ban'])->name('ban');
        Route::post('/{user}/unban', [AdminUserController::class, 'unban'])->name('unban');
        Route::post('/{user}/impersonate', [AdminUserController::class, 'impersonate'])->name('impersonate');
        Route::post('/{user}/adjust-wallet', [AdminUserController::class, 'adjustWallet'])->name('wallet');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
    });

    // Content moderation
    Route::prefix('content')->name('content.')->group(function () {
        Route::get('/reports', [AdminContentController::class, 'reports'])->name('reports');
        Route::post('/reports/{report}/action', [AdminContentController::class, 'action'])->name('reports.action');
        Route::get('/voice-posts', [AdminContentController::class, 'voicePosts'])->name('voice');
        Route::delete('/voice-posts/{post}', [AdminContentController::class, 'deleteVoicePost'])->name('voice.delete');
        Route::get('/status-posts', [AdminContentController::class, 'statusPosts'])->name('status');
        Route::delete('/status-posts/{post}', [AdminContentController::class, 'deleteStatusPost'])->name('status.delete');
    });

    // Wallet / Financials
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/withdrawals', [AdminWalletController::class, 'withdrawals'])->name('withdrawals');
        Route::post('/withdrawals/{withdrawal}/approve', [AdminWalletController::class, 'approveWithdrawal'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/reject', [AdminWalletController::class, 'rejectWithdrawal'])->name('withdrawals.reject');
        Route::get('/redemptions', [AdminWalletController::class, 'redemptions'])->name('redemptions');
        Route::post('/redemptions/{redemption}/fulfill', [AdminWalletController::class, 'fulfillRedemption'])->name('redemptions.fulfill');
        Route::get('/reward-catalog', [AdminWalletController::class, 'rewardCatalog'])->name('catalog');
        Route::post('/reward-catalog', [AdminWalletController::class, 'storeReward'])->name('catalog.store');
        Route::put('/reward-catalog/{reward}', [AdminWalletController::class, 'updateReward'])->name('catalog.update');
        Route::delete('/reward-catalog/{reward}', [AdminWalletController::class, 'deleteReward'])->name('catalog.delete');
        Route::get('/reward-rates', [AdminWalletController::class, 'rewardRates'])->name('rates');
        Route::post('/reward-rates', [AdminWalletController::class, 'updateRates'])->name('rates.update');
    });

    // Campaigns
    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::get('/', [AdminCampaignController::class, 'index'])->name('index');
        Route::post('/{campaign}/approve', [AdminCampaignController::class, 'approve'])->name('approve');
        Route::post('/{campaign}/reject', [AdminCampaignController::class, 'reject'])->name('reject');
        Route::get('/{campaign}/analytics', [AdminCampaignController::class, 'analytics'])->name('analytics');
    });

    // KYC
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/', [AdminKycController::class, 'index'])->name('index');
        Route::get('/{kyc}', [AdminKycController::class, 'show'])->name('show');
        Route::post('/{kyc}/approve', [AdminKycController::class, 'approve'])->name('approve');
        Route::post('/{kyc}/reject', [AdminKycController::class, 'reject'])->name('reject');
    });

    // Subscriptions
    Route::get('/subscriptions', [AdminSettingsController::class, 'subscriptions'])->name('subscriptions');
    Route::put('/subscriptions/{plan}', [AdminSettingsController::class, 'updatePlan'])->name('subscriptions.update');

    // Blog
    Route::prefix('blog')->name('blog.')->group(function () {
        Route::get('/', [AdminBlogController::class, 'index'])->name('index');
        Route::get('/create', [AdminBlogController::class, 'create'])->name('create');
        Route::post('/', [AdminBlogController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [AdminBlogController::class, 'edit'])->name('edit');
        Route::put('/{post}', [AdminBlogController::class, 'update'])->name('update');
        Route::delete('/{post}', [AdminBlogController::class, 'destroy'])->name('destroy');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [AdminSettingsController::class, 'index'])->name('index');
        Route::post('/general', [AdminSettingsController::class, 'updateGeneral'])->name('general');
        Route::post('/payment', [AdminSettingsController::class, 'updatePayment'])->name('payment');
        Route::post('/rewards', [AdminSettingsController::class, 'updateRewards'])->name('rewards');
        Route::post('/seo', [AdminSettingsController::class, 'updateSeo'])->name('seo');
        Route::get('/fraud', [AdminSettingsController::class, 'fraud'])->name('fraud');
        Route::post('/fraud/{flag}/action', [AdminSettingsController::class, 'fraudAction'])->name('fraud.action');
        Route::get('/audit-logs', [AdminSettingsController::class, 'auditLogs'])->name('audit');
    });
});

// Webhook routes
Route::post('/webhooks/stripe', [\App\Http\Controllers\WebhookController::class, 'stripe'])->name('webhooks.stripe');
Route::post('/webhooks/paystack', [\App\Http\Controllers\WebhookController::class, 'paystack'])->name('webhooks.paystack');
