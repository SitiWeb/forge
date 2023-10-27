<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->integer('server_id');
            $table->string('name');
            $table->integer('user_id')->nullable();
            // Add more columns as needed

            $table->timestamps();
       
        });
    
    }

    public function down()
    {
        Schema::dropIfExists('sites');
    }
};
