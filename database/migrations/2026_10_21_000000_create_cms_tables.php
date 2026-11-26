<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Semua tabel CMS dengan prefix cms_
 * Tidak termasuk: users, menus, menu_items (sudah ada).
 * Simpan di: database/migrations/
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Kategori
        Schema::create('cms_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('cms_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Posts / artikel
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

        // 3. Tags + pivot
        Schema::create('cms_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('cms_post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('cms_posts')->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained('cms_tags')->cascadeOnDelete();
            $table->primary(['post_id', 'tag_id']);
        });

        // 4. Revisi post
        Schema::create('cms_post_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('cms_posts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->unsignedInteger('revision_number')->default(1);
            $table->timestamps();

            $table->index(['post_id', 'revision_number']);
        });

        // 5. Terjemahan post (multi-bahasa)
        Schema::create('cms_post_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('cms_posts')->cascadeOnDelete();
            $table->string('locale', 10);
            $table->string('title');
            $table->string('slug');
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();

            $table->unique(['post_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });

        // 6. Komentar
        Schema::create('cms_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('cms_posts')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('cms_comments')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->text('body');
            $table->enum('status', ['pending', 'approved', 'spam', 'trash'])->default('pending');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['post_id', 'status']);
        });

        // 7. Halaman statis
        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('cms_pages')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content')->nullable();
            $table->string('featured_image')->nullable();
            $table->string('template')->default('default');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
        });

        // 8. Media library
        Schema::create('cms_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('file_name');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0); // bytes
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('mime_type');
        });

        // 9. Pengaturan situs
        Schema::create('cms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->default('general');
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, image, boolean, json
            $table->timestamps();

            $table->index('group');
        });

        // 10. Banner / slider
        Schema::create('cms_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image');
            $table->string('link_url')->nullable();
            $table->string('button_text')->nullable();
            $table->string('position')->default('home'); // lokasi tampil
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();

            $table->index(['position', 'is_active']);
        });

        // 11. Galeri + item
        Schema::create('cms_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cms_gallery_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->constrained('cms_galleries')->cascadeOnDelete();
            $table->foreignId('media_id')->nullable()->constrained('cms_media')->nullOnDelete();
            $table->string('image')->nullable();
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // 12. Pesan kontak
        Schema::create('cms_contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 30)->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_read');
        });

        // 13. Subscriber newsletter
        Schema::create('cms_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('token', 64)->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });

        // 14. Log aktivitas
        Schema::create('cms_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action'); // created, updated, deleted, login, dll
            $table->nullableMorphs('subject'); // model yang diubah
            $table->json('properties')->nullable(); // nilai lama/baru
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->nullable();

            $table->index('action');
        });

        // 15. Redirect 301/302
        Schema::create('cms_redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_url')->unique();
            $table->string('to_url');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->unsignedBigInteger('hits')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'cms_redirects',
            'cms_activity_logs',
            'cms_subscribers',
            'cms_contact_messages',
            'cms_gallery_items',
            'cms_galleries',
            'cms_banners',
            'cms_settings',
            'cms_media',
            'cms_pages',
            'cms_comments',
            'cms_post_translations',
            'cms_post_revisions',
            'cms_post_tag',
            'cms_tags',
            'cms_posts',
            'cms_categories',
        ] as $tbl) {
            Schema::dropIfExists($tbl);
        }

        Schema::enableForeignKeyConstraints();
    }
};