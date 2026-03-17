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
        Schema::table('appointments', function (Blueprint $table) {
            $table->decimal('weight', 5, 2)->nullable()->after('status');
            $table->decimal('height', 5, 2)->nullable()->after('weight');
            $table->string('blood_pressure', 20)->nullable()->after('height');
            $table->decimal('temperature', 4, 1)->nullable()->after('blood_pressure');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['weight', 'height', 'blood_pressure', 'temperature']);
        });
    }
};
