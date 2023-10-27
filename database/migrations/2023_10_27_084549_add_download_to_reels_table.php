<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reels', function (Blueprint $table) {
            DB::statement("ALTER TABLE `reels` ADD `download` INT(11) NOT NULL DEFAULT '0' AFTER `status`;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reels', function (Blueprint $table) {
            //
        });
    }
};
