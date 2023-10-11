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
		DB::statement("ALTER TABLE `banners` ADD `link` VARCHAR(255) NULL DEFAULT NULL AFTER `position`, ADD `number` INT(11) NOT NULL DEFAULT '0' AFTER `link`;");
        DB::statement("ALTER TABLE `reels` ADD `meta_title` TEXT NULL DEFAULT NULL AFTER `thumb`, ADD `meta_keyword` TEXT NULL DEFAULT NULL AFTER `meta_title`, ADD `meta_desc` TEXT NULL DEFAULT NULL AFTER `meta_keyword`;");
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
