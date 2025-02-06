<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnContractType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     if (!Schema::hasTable('en_contract_type')){
            Schema::create('en_contract_type', function (Blueprint $table){
                $table->uuid('contract_type_id');
                $table->primary('contract_type_id'); 
                $table->string('contract_type', 100); 
                $table->string('contract_description', 255); 
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_contract_type` MODIFY `contract_type_id` BINARY(16);'); 
          
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
