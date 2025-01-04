<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketPrioritiesTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_priorities', function (Blueprint $table) {
            $table->id('TicketPriorityID'); // Klucz główny
            $table->string('name'); // Nazwa priorytetu
            $table->timestamps(); // created_at i updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_priorities');
    }
}
