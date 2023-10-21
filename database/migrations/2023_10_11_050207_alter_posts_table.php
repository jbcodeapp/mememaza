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
		DB::statement("ALTER TABLE `posts` ADD `download` INT(11) NOT NULL DEFAULT '0' AFTER `status`;");
        DB::statement("ALTER TABLE `posts` ADD `meta_title` TEXT NULL DEFAULT NULL AFTER `image`, ADD `meta_keyword` TEXT NULL DEFAULT NULL AFTER `meta_title`, ADD `meta_desc` TEXT NULL DEFAULT NULL AFTER `meta_keyword`;");
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
