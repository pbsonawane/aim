<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnSoftwareCategoryAddStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('en_software_category')) {
	        if (!Schema::hasColumn('en_software_category', 'status')) {
                DB::statement("ALTER TABLE `en_software_category` ADD `status` ENUM('y', 'n', 'd') DEFAULT('y');");
				}
													 
		 } 
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
}
