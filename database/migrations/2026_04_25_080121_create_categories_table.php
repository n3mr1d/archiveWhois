<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Table for categories (Country, Gender, etc.)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., 'Indonesia', 'Male', 'News'
            $table->string('type'); // e.g., 'country', 'gender', 'general'
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Pivot table for many-to-many relationship
        Schema::create('category_pastebin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pastebin_id')->constrained('pastebins')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_pastebin');
        Schema::dropIfExists('categories');
    }
};
