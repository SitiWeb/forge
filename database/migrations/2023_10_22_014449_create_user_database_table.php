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
        Schema::create('databaseusers_databases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('database_id');
            $table->unsignedBigInteger('database_user_id');
            $table->timestamps();

            $table->unique(['database_user_id', 'database_id']);

            // $table->foreign('')->references('id')->on('database_users')->onDelete('cascade');
            // $table->foreign('table_id')->references('id')->on('databases')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('databaseusers_databases');
    }
};
