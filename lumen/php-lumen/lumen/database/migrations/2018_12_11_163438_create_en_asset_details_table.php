<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnAssetDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       if (!Schema::hasTable('en_asset_details')){
            Schema::create('en_asset_details', function (Blueprint $table){
                $table->uuid('asset_detail_id');
                $table->primary('asset_detail_id'); 
                $table->BINARY('asset_id', 16);
                $table->JSON('asset_details');
                $table->enum('auto_discovered',array('y', 'n'))->default('n'); 
                $table->text('add_comment');  
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_asset_details` MODIFY `asset_detail_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_asset_details` MODIFY `asset_id` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('en_asset_details');
    }
}
