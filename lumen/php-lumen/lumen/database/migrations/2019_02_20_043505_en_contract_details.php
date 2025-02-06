<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnContractDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('en_contract_details')){
            Schema::create('en_contract_details', function (Blueprint $table){
                $table->uuid('contract_details_id');
                $table->primary('contract_details_id'); 
		$table->integer('contract_id')->unsigned(); 
                $table->text('support', 100); 
                $table->text('description', 100); 
	        $table->string('attachments', 100);
	        $table->string('cost', 100);
		$table->json('asset_id');     
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_contract_details` MODIFY `contract_details_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_contract_details` MODIFY `contract_id` BINARY(16);'); 
          
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
