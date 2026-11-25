<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cms_comments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('cms_post_id')
                ->constrained('cms_posts', 'id', 'fk_cms_comments_post')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Self-referencing untuk balasan komentar (nested reply 1 level / multi-level)
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('cms_comments', 'id', 'fk_cms_comments_parent')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Terisi jika komentator sedang login (guru/siswa/admin); null jika tamu publik
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users', 'id', 'fk_cms_comments_user')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('author_name', 100);
            $table->string('author_email', 150);
            $table->text('content');

            // Moderasi komentar: pending, approved, spam, rejected
            $table->enum('status', ['pending', 'approved', 'spam', 'rejected'])->default('pending');

            // Jejak audit & anti-spam
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 255)->nullable();

            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users', 'id', 'fk_cms_comments_approved_by')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->timestamps();
            $table->softDeletes();

            // Composite index untuk query halaman detail artikel publik
            $table->index(['cms_post_id', 'status', 'parent_id', 'created_at'], 'idx_cms_comments_post_approved');
            $table->index(['status', 'created_at'], 'idx_cms_comments_moderation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_comments');
    }
};