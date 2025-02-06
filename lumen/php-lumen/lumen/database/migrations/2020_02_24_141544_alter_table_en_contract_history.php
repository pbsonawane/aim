<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEnContractHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (Schema::hasTable('en_contract_history')) {
		                     if (!Schema::hasColumn('en_contract_history', 'notify_to_id')) {
					                             DB::statement('ALTER TABLE `en_contract_history` ADD `notify_to_id` BINARY(16) ;');
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
