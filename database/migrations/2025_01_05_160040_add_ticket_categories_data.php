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
        DB::table('ticket_categories')->insert([
            ['name' => 'Zadanie', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pytanie', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Problem', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Błąd w programie', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Błąd inny', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('ticket_categories')->whereIn('name', [
            'Zadanie', 'Pytanie', 'Problem', 'Błąd w programie', 'Błąd inny'
        ])->delete();
    }
};
