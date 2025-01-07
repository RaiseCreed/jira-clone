<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttachmentAndAssignedToToTicketsTable extends Migration
{
    
    
     
      @return void
     
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('attachment')->nullable()
            $table->unsignedBigInteger('assigned_to')->nullable()->after('description'); 
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null'); 
        });
    }


      @return void
     
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['assigned_to']);
            $table->dropColumn('assigned_to');
            $table->dropColumn('attachment');
        });
    }
}
