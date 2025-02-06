<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnFormDataPrBvDcLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('en_form_data_pr', function (Blueprint $table) {
            DB::statement("ALTER TABLE `en_form_data_pr` ADD `bv_id` BINARY(16) AFTER approval_req;");

            DB::statement("ALTER TABLE `en_form_data_pr` ADD `dc_id` BINARY(16) AFTER bv_id;");

            DB::statement("ALTER TABLE `en_form_data_pr` ADD `location_id` BINARY(16) AFTER dc_id;"); 
	    
            DB::statement("ALTER TABLE en_form_data_pr  ADD `status` ENUM('pending approval', 'open','partially approved','approved','closed','cancelled','deleted') AFTER location_id;");
        });
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
