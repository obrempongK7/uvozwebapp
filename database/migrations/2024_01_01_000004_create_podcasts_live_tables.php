<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cover_artwork')->nullable();
            $table->string('category')->nullable();
            $table->json('tags')->nullable();
            $table->enum('type', ['free', 'premium', 'paid'])->default('free');
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('status', ['active', 'inactive', 'draft'])->default('active');
            $table->boolean('is_explicit')->default(false);
            $table->string('language', 10)->default('en');
            $table->string('country', 3)->nullable();
            $table->string('rss_feed_url')->nullable();
            $table->unsignedBigInteger('subscribers_count')->default(0);
            $table->unsignedBigInteger('episodes_count')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('podcast_episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('show_notes')->nullable();
            $table->string('audio_file');
            $table->unsignedInteger('duration')->default(0);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedInteger('episode_number')->nullable();
            $table->string('season_number')->nullable();
            $table->enum('type', ['full', 'trailer', 'bonus'])->default('full');
            $table->json('tags')->nullable();
            $table->enum('access', ['free', 'subscriber_only', 'paid'])->default('free');
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('status', ['draft', 'scheduled', 'published'])->default('published');
            $table->boolean('is_explicit')->default(false);
            $table->unsignedBigInteger('plays_count')->default(0);
            $table->unsignedBigInteger('downloads_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('comments_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('podcast_subscribers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('podcast_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['free', 'paid'])->default('free');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['podcast_id', 'user_id']);
        });

        Schema::create('live_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('host_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('type', ['public', 'private', 'password_protected'])->default('public');
            $table->string('password')->nullable();
            $table->enum('status', ['scheduled', 'live', 'ended', 'cancelled'])->default('scheduled');
            $table->boolean('is_recorded')->default(false);
            $table->string('recording_file')->nullable();
            $table->boolean('is_ticketed')->default(false);
            $table->decimal('ticket_price', 8, 2)->default(0);
            $table->unsignedInteger('max_participants')->nullable();
            $table->unsignedBigInteger('listeners_count')->default(0);
            $table->unsignedBigInteger('peak_listeners')->default(0);
            $table->string('room_token')->unique()->nullable();
            $table->string('country', 3)->nullable();
            $table->json('tags')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('live_room_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('live_rooms')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['host', 'co_host', 'speaker', 'listener'])->default('listener');
            $table->boolean('is_muted')->default(false);
            $table->boolean('has_raised_hand')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamp('left_at')->nullable();
            $table->timestamps();

            $table->unique(['room_id', 'user_id']);
        });

        Schema::create('live_room_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('live_rooms')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['text', 'reaction', 'gift', 'system'])->default('text');
            $table->text('content')->nullable();
            $table->string('gift_type')->nullable();
            $table->unsignedInteger('gift_amount')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('room_recordings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('live_rooms')->cascadeOnDelete();
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedInteger('duration')->default(0);
            $table->enum('status', ['processing', 'ready', 'failed'])->default('processing');
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_recordings');
        Schema::dropIfExists('live_room_messages');
        Schema::dropIfExists('live_room_participants');
        Schema::dropIfExists('live_rooms');
        Schema::dropIfExists('podcast_subscribers');
        Schema::dropIfExists('podcast_episodes');
        Schema::dropIfExists('podcasts');
    }
};
