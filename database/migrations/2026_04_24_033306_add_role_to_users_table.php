<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->enum('role', ['member', 'king', 'admin'])->default('member')->after('username');
            $table->text('bio')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('bio');
            $table->boolean('is_banned')->default(false)->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'bio', 'avatar', 'is_banned']);
        });
    }
};
