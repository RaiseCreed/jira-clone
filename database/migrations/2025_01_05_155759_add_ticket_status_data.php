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
            ['name' => 'Nowe', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Analiza', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'W trakcie pracy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Testy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gotowe', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Zamknięte', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('ticket_statuses')->whereIn('name', [
            'Nowe', 'Analiza', 'W trakcie pracy', 'Testy', 'Gotowe', 'Zamknięte'
        ])->delete();
    }
};
