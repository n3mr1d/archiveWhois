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
        Schema::table('pastebins', function (Blueprint $table) {
            $table->unsignedBigInteger('views')->default(0)->after('banner_path');
            $table->string('language', 50)->nullable()->after('views')->comment('Syntax highlighting language');
            $table->enum('visibility', ['public', 'unlisted', 'private'])->default('public')->after('language');
            $table->timestamp('expires_at')->nullable()->after('visibility');
            $table->string('password')->nullable()->after('expires_at')->comment('Password-protected paste');
            $table->string('syntax_theme', 50)->nullable()->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pastebins', function (Blueprint $table) {
            $table->dropColumn(['views', 'language', 'visibility', 'expires_at', 'password', 'syntax_theme']);
        });
    }
};
