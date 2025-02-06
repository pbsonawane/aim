<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnContract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         
                DB::statement("ALTER TABLE `en_contract` MODIFY `parent_contract` BINARY(16);");
                DB::statement('ALTER TABLE `en_contract` MODIFY `contract_type_id` BINARY(16);');
		
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
