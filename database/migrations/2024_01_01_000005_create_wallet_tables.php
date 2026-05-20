<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('points_balance')->default(0);
            $table->unsignedBigInteger('pending_points')->default(0);
            $table->decimal('usd_balance', 12, 2)->default(0);
            $table->decimal('pending_usd', 12, 2)->default(0);
            $table->decimal('total_earned_usd', 12, 2)->default(0);
            $table->decimal('total_withdrawn_usd', 12, 2)->default(0);
            $table->unsignedBigInteger('today_points_earned')->default(0);
            $table->date('today_date')->nullable();
            $table->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', [
                'points_earned', 'points_deducted', 'points_redeemed',
                'usd_earned', 'usd_deducted', 'withdrawal', 'subscription_charge',
                'tip_received', 'tip_sent', 'gift_received', 'referral_bonus',
                'campaign_reward', 'fraud_reversal', 'admin_adjustment'
            ]);
            $table->unsignedBigInteger('points_amount')->default(0);
            $table->decimal('usd_amount', 12, 2)->default(0);
            $table->string('description');
            $table->string('reference')->nullable()->unique();
            $table->morphs('source'); // what triggered this transaction
            $table->enum('status', ['pending', 'completed', 'failed', 'reversed'])->default('completed');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'type', 'created_at']);
        });

        Schema::create('reward_catalog', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('category', [
                'cash', 'airtime', 'data', 'electronics', 'travel',
                'gift_cards', 'vouchers', 'tickets', 'merchandise'
            ]);
            $table->unsignedBigInteger('points_required');
            $table->decimal('usd_value', 8, 2)->default(0);
            $table->unsignedInteger('stock_quantity')->nullable();
            $table->enum('delivery_method', ['digital', 'physical', 'automatic'])->default('digital');
            $table->json('available_countries')->nullable(); // null = all countries
            $table->unsignedInteger('processing_days')->default(3);
            $table->text('terms')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('redemptions_count')->default(0);
            $table->timestamps();
        });

        Schema::create('reward_redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reward_id')->constrained('reward_catalog')->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('points_spent');
            $table->decimal('usd_value', 8, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'processing', 'fulfilled', 'rejected', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->text('admin_notes')->nullable();
            $table->json('delivery_details')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('fulfilled_at')->nullable();
            $table->timestamps();
        });

        Schema::create('reward_shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('phone');
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country', 3);
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('wallet_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->enum('method', ['mobile_money', 'bank_transfer', 'paypal', 'usdt'])->default('mobile_money');
            $table->json('payment_details');
            $table->enum('status', ['pending', 'approved', 'processing', 'completed', 'rejected', 'cancelled'])->default('pending');
            $table->string('transaction_reference')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->text('admin_notes')->nullable();
            $table->decimal('fee', 8, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('reward_rates', function (Blueprint $table) {
            $table->id();
            $table->string('action_type'); // voice_play, comment, status_view, etc.
            $table->unsignedInteger('points_value');
            $table->string('country', 3)->nullable(); // null = global
            $table->unsignedInteger('daily_limit')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['action_type', 'country']);
        });

        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('tippable');
            $table->unsignedBigInteger('points_amount');
            $table->string('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tips');
        Schema::dropIfExists('reward_rates');
        Schema::dropIfExists('withdrawals');
        Schema::dropIfExists('reward_shipping_addresses');
        Schema::dropIfExists('reward_redemptions');
        Schema::dropIfExists('reward_catalog');
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
    }
};
