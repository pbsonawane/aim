<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;

class CreateEnContractTypeTable extends Migration
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
				$table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->enum('is_default',array('y', 'n'))->default('n');   
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_contract_type` MODIFY `contract_type_id` BINARY(16);'); 
          
        } 
		if (Schema::hasTable('en_contract_type'))
        {
            Artisan::call( 'db:seed', ['--class' => 'EnContractTypeTableSeeder','--force' => true ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_contract_type');
    }
}
