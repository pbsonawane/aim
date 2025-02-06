<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableEnCostCenterLocationDatatype extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (Schema::hasTable('en_cost_centers')) {
			Schema::table('en_cost_centers', function (Blueprint $table) {
			  
				DB::statement('ALTER TABLE `en_cost_centers` MODIFY `locations` BINARY(16);');
				DB::statement('ALTER TABLE `en_cost_centers` DROP COLUMN `owner_id`;');

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
