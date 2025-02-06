<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddCommentEnFormDataPr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('en_form_data_pr')){
	    DB::statement("ALTER TABLE  `en_form_data_pr` MODIFY COLUMN `status` ENUM('pending approval','open','partially approved','approved','closed','cancelled','deleted', 'rejected');");
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
