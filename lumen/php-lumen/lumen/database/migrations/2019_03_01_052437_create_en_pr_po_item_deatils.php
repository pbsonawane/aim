<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnPrPoItemDeatils extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          if (!Schema::hasTable('en_pr_po_asset_details')){
            Schema::create('en_pr_po_asset_details', function (Blueprint $table){
                $table->uuid('pr_po_asset_id');
                $table->primary('pr_po_asset_id'); 
                $table->BINARY('pr_id', 16); 
                $table->BINARY('po_id', 16); 
                $table->enum('asset_type',array('pr', 'po'));
                $table->json('asset_details');                 
                $table->BINARY('created_by', 16); 
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_pr_po_asset_details` MODIFY `pr_po_asset_id` BINARY(16);');    
 
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_pr_po_asset_details');
    }
}
