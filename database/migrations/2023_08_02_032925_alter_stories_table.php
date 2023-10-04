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
        DB::statement("ALTER TABLE `stories` ADD `link` VARCHAR(255) NULL DEFAULT NULL AFTER `story`;");
		
		DB::statement("ALTER TABLE `posts` ADD `like` INT(11) NOT NULL DEFAULT '0' AFTER `image`, ADD `view` INT(11) NOT NULL DEFAULT '0' AFTER `like`, ADD `share` INT(11) NOT NULL DEFAULT '0' AFTER `view`, ADD `comment` INT(11) NOT NULL DEFAULT '0' AFTER `share`;");
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
