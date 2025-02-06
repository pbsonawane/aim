<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnContractTable extends Migration
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
                $table->integer('parent_contract')->unsigned(); 
                $table->string('contract_name', 100); 
                $table->string('contractid', 100); 
		        $table->integer('contract_type_id')->unsigned(); 
		        $table->enum('renewed',array('y', 'n'))->default('n');   
                $table->date('from_date'); 				
                $table->date('to_date'); 	
                $table->enum('contract_status',array('active', 'expired'))->default('active');
                $table->enum('status',array('y', 'n','d'))->default('y');
                $table->integer('primary_contract')->unsigned();
                $table->integer('user_id')->unsigned();
                $table->integer('renewed_to')->unsigned();
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_contract` MODIFY `contract_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_contract` MODIFY `vendor_id` BINARY(16);'); 
            DB::statement("ALTER TABLE `en_contract` MODIFY `parent_contract` BINARY(16);");
            DB::statement('ALTER TABLE `en_contract` MODIFY `contract_type_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_contract` MODIFY `primary_contract` BINARY(16);');
            DB::statement('ALTER TABLE `en_contract` MODIFY `user_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_contract` MODIFY `renewed_to` BINARY(16);');
         } 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_contract');
    }
}
