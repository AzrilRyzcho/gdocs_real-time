<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('last_editor_id', 50)->nullable()->after('user_id');
            $table->string('last_editor_name', 100)->nullable()->after('last_editor_id');
            $table->string('last_editor_color', 20)->nullable()->after('last_editor_name');
            $table->timestamp('last_edited_at')->nullable()->after('last_editor_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn(['last_editor_id', 'last_editor_name', 'last_editor_color', 'last_edited_at']);
        });
    }
};
