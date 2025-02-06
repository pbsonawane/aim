<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnContract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_contract')){
            Schema::create('en_contract', function (Blueprint $table){
                $table->uuid('contract_id');
                $table->primary('contract_id'); 
		$table->integer('vendor_id')->unsigned(); 
                $table->string('contract_name', 100); 
                $table->string('contractid', 100); 
		$table->integer('contract_type_id')->unsigned(); 
		$table->enum('renewed',array('y', 'n'));   
		$table->enum('status',array('Active', 'Expired'));
                $table->date('from_date'); 				
                $table->date('to_date'); 				
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_contract` MODIFY `contract_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_contract` MODIFY `vendor_id` BINARY(16);'); 
	    DB::statement('ALTER TABLE `en_contract` MODIFY `contract_type_id` BINARY(16);'); 
          
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
