<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttachmentsTable extends Migration
{
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id('AttachmentID'); 
            $table->foreignId('ticket_id')->constrained('tickets'); 
            $table->binary('blob'); 
            $table->dateTime('date'); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('attachments');
    }
}
