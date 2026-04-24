<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paste_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paste_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->foreignId('parent_id')->nullable()->constrained('paste_comments')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['paste_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paste_comments');
    }
};
