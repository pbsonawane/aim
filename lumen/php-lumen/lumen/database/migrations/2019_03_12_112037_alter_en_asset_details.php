<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnAssetDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       if (Schema::hasTable('en_asset_details')) {
            Schema::table('en_asset_details', function (Blueprint $table) {    
                DB::statement('ALTER TABLE `en_asset_details` ADD `vendor_id` BINARY(16) ;');       
                DB::statement('ALTER TABLE `en_asset_details` ADD `purchasecost` varchar(100);');
                DB::statement('ALTER TABLE `en_asset_details` ADD `acquisitiondate` timestamp;');
                DB::statement('ALTER TABLE `en_asset_details` ADD `expirydate` timestamp;');
                DB::statement('ALTER TABLE `en_asset_details` ADD `warrantyexpirydate` timestamp;');
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
