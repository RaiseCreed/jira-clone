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
            ['name' => 'Task', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Question', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Problem', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Software error', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Other issue', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('ticket_categories')->whereIn('name', [
            'Task', 'Question', 'Problem', 'Software error', 'Other issue'
        ])->delete();
    }
};
