<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('seat_ids');
            $table->foreignId('seat_id')->nullable()->constrained()->onDelete('cascade');
        });

        DB::statement('UPDATE reservations SET seat_id = 1 WHERE seat_id IS NULL'); // Replace 1 with an existing seat_id

        Schema::table('reservations', function (Blueprint $table) {
            // Step 3: Modify the column to be NOT NULL
            $table->foreignId('seat_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            //
        });
    }
};
