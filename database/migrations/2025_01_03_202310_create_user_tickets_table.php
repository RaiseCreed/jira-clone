<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('user_tickets', function (Blueprint $table) {
            $table->id('UserTicketID'); 
            $table->foreignId('ticket_id')->constrained('tickets'); 
            $table->foreignId('user_id')->constrained('users'); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_tickets');
    }
}
