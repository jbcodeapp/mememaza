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
        DB::statement("ALTER TABLE `banners` ADD `meta_title` TEXT NULL DEFAULT NULL AFTER `position`, ADD `meta_keyword` TEXT NULL DEFAULT NULL AFTER `meta_title`, ADD `meta_desc` TEXT NULL DEFAULT NULL AFTER `meta_keyword`;");
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
