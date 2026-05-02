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
        Schema::table('paste_revisions', function (Blueprint $table) {
            $table->string('edit_summary')->nullable()->after('description');
            $table->boolean('is_minor')->default(false)->after('edit_summary');
            $table->integer('version_number')->default(1)->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paste_revisions', function (Blueprint $table) {
            $table->dropColumn(['edit_summary', 'is_minor', 'version_number']);
        });
    }
};
