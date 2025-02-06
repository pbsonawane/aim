<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnCitypesTable extends Migration
{

    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_ci_types')){
            Schema::create('en_ci_types', function (Blueprint $table){
                $table->uuid('ci_type_id');
                $table->primary('ci_type_id'); 
                $table->string('citype', 50); 
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_ci_types` MODIFY `ci_type_id` BINARY(16);'); 
        }
    }   

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('en_citypes');
    }
}

