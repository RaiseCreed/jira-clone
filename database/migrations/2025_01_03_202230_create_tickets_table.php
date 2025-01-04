<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('TicketID'); 
            $table->foreignId('ticket_category_id')->constrained('ticket_categories');
            $table->foreignId('ticket_priority_id')->constrained('ticket_priorities'); 
            $table->foreignId('ticket_status_id')->constrained('ticket_statuses'); 
            $table->string('title');
            $table->dateTime('date'); 
            $table->dateTime('deadline'); 
            $table->dateTime('date_end')->nullable();
            $table->text('content'); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
