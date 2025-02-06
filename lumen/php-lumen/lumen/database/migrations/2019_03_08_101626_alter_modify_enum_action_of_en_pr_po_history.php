<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterModifyEnumActionOfEnPrPoHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	if (Schema::hasTable('en_pr_po_history')){
	    DB::statement("ALTER TABLE  `en_pr_po_history` MODIFY COLUMN `action` ENUM('pending approval','open','partially approved','approved','partially received','item received','closed','cancelled','deleted', 'rejected', 'notifyagain', 'notifyowner', 'notifyvendor');");

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
