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
        Schema::create('wordpress_admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id');
            $table->string('username');
            $table->integer('wordpress_user_id'); // You should consider encrypting passwords using Laravel's Hash::make()
            $table->timestamps();

           
        });
    }

    public function down()
    {
        Schema::dropIfExists('wordpress_admins');
    }
};
