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
        DB::statement("ALTER TABLE `posts` ADD `category_id` INT(11) NOT NULL AFTER `title`;");
		
		DB::statement("ALTER TABLE `reels` ADD `category_id` INT(11) NOT NULL AFTER `reel`;");
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
