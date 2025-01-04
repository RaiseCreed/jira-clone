<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('UserID'); 
            $table->foreignId('role_id')->constrained('roles'); 
            $table->string('email')->unique(); 
            $table->string('name'); 
            $table->string('s_name'); 
            $table->string('login')->unique(); 
            $table->string('pass'); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
