<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id('TicketCommentID'); 
            $table->foreignId('user_ticket_id')->constrained('user_tickets'); 
            $table->string('user'); 
            $table->text('comment'); 
            $table->dateTime('date'); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_comments');
    }
}
