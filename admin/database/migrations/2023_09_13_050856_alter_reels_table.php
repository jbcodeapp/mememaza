<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `reels` CHANGE `reel_type` `reel_type` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1:video link, 2:upload video, 3:Image';");
		DB::statement("ALTER TABLE `reels` ADD `thumb` VARCHAR(255) NULL DEFAULT NULL AFTER `link`;");
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
