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
        Schema::table('databases', function (Blueprint $table) {
            $table->unsignedBigInteger('server_id')->after('table_user_id'); // Change the data type as needed
            $table->string('status')->after('server_id'); // Change the data type as needed
        });
    }

    public function down()
    {
        Schema::table('databases', function (Blueprint $table) {
            $table->dropColumn('server_id');
            $table->dropColumn('status');
        });
    }
};
