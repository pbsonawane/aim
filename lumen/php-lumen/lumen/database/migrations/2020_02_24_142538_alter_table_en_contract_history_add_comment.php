<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEnContractHistoryAddComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if (Schema::hasTable('en_contract_history')) {
	                       if (!Schema::hasColumn('en_contract_history', 'comment')) {
				                              DB::statement('ALTER TABLE `en_contract_history` ADD `comment` varchar(255) ;');
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
