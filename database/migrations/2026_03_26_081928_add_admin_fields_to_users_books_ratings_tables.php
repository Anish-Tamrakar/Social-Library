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
        Schema::table('books', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false);
            $table->string('admin_status')->default('approved'); // pending, approved, rejected
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->boolean('is_flagged')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'admin_status']);
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn('is_flagged');
        });
    }
};
