<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cms_posts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users', 'id', 'fk_cms_posts_user')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('cms_category_id')
                ->nullable()
                ->constrained('cms_categories', 'id', 'fk_cms_posts_category')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('title', 191);
            $table->string('slug', 191);
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('featured_image', 191)->nullable();

            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->unsignedBigInteger('view_count')->default(0);

            // SEO Meta
            $table->string('meta_title', 191)->nullable();
            $table->string('meta_description', 191)->nullable();
            $table->string('meta_keywords', 191)->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Virtual column untuk unique slug yang kompatibel dengan softDeletes
            $table->boolean('is_not_deleted')
                ->virtualAs('IF(deleted_at IS NULL, 1, NULL)');

            $table->unique(['slug', 'is_not_deleted'], 'uq_cms_posts_slug_active');
            $table->index(['status', 'published_at'], 'idx_cms_posts_status_pub');
            $table->index(['is_featured', 'status'], 'idx_cms_posts_featured_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_posts');
    }
};