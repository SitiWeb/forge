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
        Schema::table('database_users', function (Blueprint $table) {
            // Add the new 'site_id' column as an integer that can be null
            $table->integer('site_id')->nullable()->after('server_id');

        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('database_users', function (Blueprint $table) {
            // Drop the 'site_id' column if you ever need to rollback the migration
            $table->dropColumn('site_id');
        });
    }
};
