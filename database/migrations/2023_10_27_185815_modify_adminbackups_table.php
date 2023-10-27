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
        Schema::table('adminbackups', function (Blueprint $table) {
            // Add columns before timestamps
            $table->string('backup_id')->before('created_at');
            $table->string('archive')->before('created_at');
            $table->string('barchive')->before('created_at');
            $table->dateTime('start')->before('created_at');
            $table->dateTime('time')->before('created_at');
            $table->unsignedBigInteger('repository_id')->before('created_at');
            $table->foreign('repository_id')->references('id')->on('repositories');
           
            $table->dropColumn('path');
            $table->dropColumn('repository');
        });
    }

    public function down()
    {
        Schema::table('adminbackups', function (Blueprint $table) {
            // Reverse the changes in the "up" method
            $table->string('path')->after('name');
            $table->string('repository')->after('path');
            $table->dropColumn('backup_id');
            $table->dropColumn('archive');
            $table->dropColumn('barchive');
            $table->dropColumn('start');
            $table->dropColumn('time');
            $table->dropForeign(['repository_id']);
            $table->dropColumn('repository_id');
        });
    }
};
