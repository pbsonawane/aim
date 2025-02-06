<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterActionEnPrPoHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (Schema::hasTable('en_pr_po_history')) {
            
            if (Schema::hasColumn('en_pr_po_history', 'action'))
            {
                DB::statement("ALTER TABLE en_pr_po_history change action action ENUM('pending approval','open','partially approved','approved','partially received','item received','closed','cancelled','deleted','rejected','notifyagain','notifyowner','notifyvendor','updated','created','ordered') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  DEFAULT 'pending approval';");
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
        if (Schema::hasTable('en_pr_po_history')) {
            
            if (Schema::hasColumn('en_pr_po_history', 'action'))
            {
                DB::statement("ALTER TABLE en_pr_po_history change action action ENUM('pending approval','open','partially approved','approved','partially received','item received','closed','cancelled','deleted','rejected','notifyagain','notifyowner','notifyvendor') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  DEFAULT 'pending approval';");
            }
        }
    }
}
