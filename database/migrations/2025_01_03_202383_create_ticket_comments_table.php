<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
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
