<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlteroneEnPrPoItemDeatils extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('en_pr_po_asset_details', function (Blueprint $table){
                $table->dropColumn('po_id');
                $table->dropColumn('pr_id');
                $table->BINARY('pr_po_id', 16); 
                
            });
          DB::statement('ALTER TABLE `en_pr_po_asset_details` MODIFY `pr_po_id` BINARY(16);');

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
