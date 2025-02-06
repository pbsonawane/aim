<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnAssetRelationshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_asset_relationship')){
            Schema::create('en_asset_relationship', function (Blueprint $table){
                $table->uuid('asset_relationship_id');
                $table->primary('asset_relationship_id'); 
                $table->uuid('parent_asset_id'); 
                $table->uuid('child_asset_id'); 
                $table->uuid('rel_type_id'); 
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_asset_relationship` MODIFY `asset_relationship_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_asset_relationship` MODIFY `parent_asset_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_asset_relationship` MODIFY `child_asset_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_asset_relationship` MODIFY `rel_type_id` BINARY(16);'); 
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_asset_relationship');
    }
}
