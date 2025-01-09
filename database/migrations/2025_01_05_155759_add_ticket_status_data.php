<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('ticket_statuses')->insert([
            ['name' => 'New', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Analysis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ongoing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Testing', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ready', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Closed', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rejected', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('ticket_statuses')->whereIn('name', [
            'New', 'Analysis', 'Ongoing', 'Testing', 'Ready', 'Closed', 'Rejected'
        ])->delete();
    }
};
