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
        Schema::table('sites', function (Blueprint $table) {
            
            $table->string('php_version')->nullable()->after('user_id');
            $table->boolean('is_secured')->default(false)->after('user_id');
            $table->json('aliases')->nullable()->after('user_id');
            $table->string('directory')->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('php_version');
            $table->dropColumn('is_secured');
            $table->dropColumn('aliases');
            $table->dropColumn('directory');
        });
    }
};
