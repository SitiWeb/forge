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
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->integer('forge_id');
            $table->string('name');
            $table->string('size');
            $table->string('region');
            $table->ipAddress('ip_address');
            $table->ipAddress('private_ip_address');
            $table->string('php_version');
            $table->timestamp('server_created_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servers');
    }

   
};
