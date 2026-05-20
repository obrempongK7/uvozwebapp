<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voice_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('audio_file');
            $table->string('audio_format', 10)->default('mp3');
            $table->unsignedInteger('duration')->default(0); // seconds
            $table->unsignedBigInteger('file_size')->default(0); // bytes
            $table->string('cover_image')->nullable();
            $table->json('hashtags')->nullable();
            $table->json('mentions')->nullable();
            $table->enum('visibility', ['public', 'followers', 'private'])->default('public');
            $table->enum('status', ['draft', 'published', 'processing', 'failed'])->default('published');
            $table->unsignedBigInteger('plays_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('comments_count')->default(0);
            $table->unsignedBigInteger('reposts_count')->default(0);
            $table->unsignedBigInteger('shares_count')->default(0);
            $table->boolean('comments_enabled')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->string('country', 3)->nullable();
            $table->decimal('engagement_score', 10, 4)->default(0);
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['status', 'published_at']);
            $table->index('engagement_score');
        });

        Schema::create('status_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['text', 'image', 'video', 'link'])->default('text');
            $table->text('content')->nullable();
            $table->string('media_file')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_title')->nullable();
            $table->string('link_image')->nullable();
            $table->json('contact_buttons')->nullable(); // whatsapp, telegram, etc.
            $table->boolean('is_monetized')->default(false);
            $table->unsignedInteger('view_price')->default(0); // points per view
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('comments_count')->default(0);
            $table->unsignedBigInteger('contact_clicks')->default(0);
            $table->enum('visibility', ['public', 'followers', 'private'])->default('public');
            $table->enum('status', ['draft', 'published', 'expired'])->default('published');
            $table->string('country', 3)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('expires_at');
        });

        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('reactable'); // voice_posts, status_posts, etc.
            $table->enum('type', ['like', 'love', 'haha', 'wow', 'sad', 'angry'])->default('like');
            $table->timestamps();

            $table->unique(['user_id', 'reactable_id', 'reactable_type']);
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('commentable');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('content')->nullable();
            $table->enum('type', ['text', 'voice'])->default('text');
            $table->string('voice_file')->nullable();
            $table->unsignedInteger('voice_duration')->nullable();
            $table->unsignedBigInteger('likes_count')->default(0);
            $table->unsignedBigInteger('replies_count')->default(0);
            $table->boolean('is_flagged')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['commentable_id', 'commentable_type', 'parent_id']);
            $table->foreign('parent_id')->references('id')->on('comments')->nullOnDelete();
        });

        Schema::create('reposts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('repostable');
            $table->text('quote')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'repostable_id', 'repostable_type']);
        });

        Schema::create('saves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('saveable');
            $table->timestamps();

            $table->unique(['user_id', 'saveable_id', 'saveable_type']);
        });

        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->morphs('reportable');
            $table->string('reason');
            $table->text('details')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'actioned', 'dismissed'])->default('pending');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('hashtags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedBigInteger('posts_count')->default(0);
            $table->boolean('is_trending')->default(false);
            $table->timestamps();
        });

        Schema::create('post_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->morphs('viewable');
            $table->string('ip_address', 45)->nullable();
            $table->string('device_fingerprint')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['viewable_id', 'viewable_type', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_views');
        Schema::dropIfExists('hashtags');
        Schema::dropIfExists('reports');
        Schema::dropIfExists('saves');
        Schema::dropIfExists('reposts');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('reactions');
        Schema::dropIfExists('status_posts');
        Schema::dropIfExists('voice_posts');
    }
};
