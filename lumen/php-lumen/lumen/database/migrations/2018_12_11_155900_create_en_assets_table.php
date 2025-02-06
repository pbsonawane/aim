<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_assets')){
            Schema::create('en_assets', function (Blueprint $table){
                $table->uuid('asset_id');
                $table->primary('asset_id'); 
                $table->string('asset_tag', 50)->nullable($value = false);
                $table->string('display_name', 100);
                $table->BINARY('bv_id', 16);
                $table->BINARY('location_id', 16);
                $table->BINARY('parent_asset_id', 16);
                $table->BINARY('object_id', 16);
                $table->BINARY('ci_templ_id', 16);
                $table->enum('ci_templ_type',array('default', 'custom'));
                $table->enum('asset_status',array('in_store', 'in_use', 'in_repair','expired','disposed'))->default('in_store');  
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_assets` MODIFY `asset_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_assets` MODIFY `bv_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_assets` MODIFY `location_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_assets` MODIFY `parent_asset_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_assets` MODIFY `object_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_assets` MODIFY `ci_templ_id` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('en_assets');
    }
}
