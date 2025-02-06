<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterStatusEnFormDataPo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (Schema::hasTable('en_form_data_po')) {
            
            if (Schema::hasColumn('en_form_data_po', 'status'))
            {
                DB::statement("ALTER TABLE en_form_data_po change status status ENUM('pending approval','open','partially approved','approved','partially received','item received','closed','cancelled','deleted','rejected','ordered')  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  DEFAULT 'pending approval';");
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
        if (Schema::hasTable('en_form_data_po')) {
            
            if (Schema::hasColumn('en_form_data_po', 'status'))
            {
                DB::statement("ALTER TABLE en_form_data_po change status status ENUM('pending approval','open','partially approved','approved','partially received','item received','closed','cancelled','deleted')  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  DEFAULT 'pending approval';");
            }
        }
    }
}
