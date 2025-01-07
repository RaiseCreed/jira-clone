<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('ticket_category_id')->nullable()->constrained('ticket_categories')->onDelete('set null');
            $table->foreignId('ticket_priority_id')->nullable()->constrained('ticket_priorities')->onDelete('set null');
            $table->foreignId('ticket_status_id')->nullable()->constrained('ticket_statuses')->onDelete('set null');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('worker_id')->nullable()->constrained('users')->onDelete('set null');
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
