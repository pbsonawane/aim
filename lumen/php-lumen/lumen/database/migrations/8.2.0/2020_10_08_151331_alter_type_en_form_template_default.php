<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTypeEnFormTemplateDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (Schema::hasTable('en_form_template_default')) {
            
            if (Schema::hasColumn('en_form_template_default', 'type'))
            {
                DB::statement("ALTER TABLE en_form_template_default change type  type ENUM('po','warranty','service','cr','incident','problem','config','credentials')   CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  DEFAULT 'config';");
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
        if (Schema::hasTable('en_form_template_default')) {
            
            if (Schema::hasColumn('en_form_template_default', 'type'))
            {
                DB::statement("ALTER TABLE en_form_template_default change type  type ENUM('po','warranty','service','cr','incident','problem','config')   CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL  DEFAULT 'config';");
            }
        }
    }
}
