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
        DB::table('ticket_priorities')->insert([
            ['name' => 'SLA-0', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SLA-1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SLA-2', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SLA-3', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('ticket_priorities')->whereIn('name', [
            'SLA-0', 'SLA-1', 'SLA-2', 'SLA-3'
        ])->delete();
    }
};
