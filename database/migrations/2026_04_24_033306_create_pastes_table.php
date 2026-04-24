<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pastes', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 12)->unique()->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->longText('content');
            $table->string('language', 50)->default('plaintext');
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('public');
            $table->string('password')->nullable();
            $table->boolean('is_encrypted')->default(false);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->string('guest_name')->nullable(); // anonymous uploader display name
            $table->string('guest_token')->nullable(); // for anonymous edit/delete
            $table->timestamps();
            $table->softDeletes();

            $table->index(['visibility', 'created_at']);
            $table->index(['user_id', 'visibility']);
            $table->fullText(['title', 'description', 'content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pastes');
    }
};
