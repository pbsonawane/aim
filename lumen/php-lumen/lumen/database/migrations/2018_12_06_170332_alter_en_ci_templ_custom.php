<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnCiTemplCustom extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('en_ci_templ_custom')) {
            Schema::table('en_ci_templ_custom', function (Blueprint $table) {
                DB::statement('ALTER TABLE `en_ci_templ_custom` ADD `prefix` varchar(100) ;');
                DB::statement('ALTER TABLE `en_ci_templ_custom` ADD `variable_name` varchar(100) ;');
            });
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
