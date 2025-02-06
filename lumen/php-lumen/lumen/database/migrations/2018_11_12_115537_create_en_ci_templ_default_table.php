<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnCiTemplDefaultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_ci_templ_default')){
            Schema::create('en_ci_templ_default', function (Blueprint $table){
                $table->uuid('ci_templ_id');
                $table->primary('ci_templ_id'); 
                $table->string('ci_name', 50); 
                $table->BINARY('ci_type_id', 16); 
                $table->json('default_attributes');
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_ci_templ_default` MODIFY `ci_templ_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_ci_templ_default` MODIFY `ci_type_id` BINARY(16);'); 
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('en_ci_templ_default');
    }
}
