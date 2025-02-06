<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnCiTemplCustomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('en_ci_templ_custom')){
            Schema::create('en_ci_templ_custom', function (Blueprint $table){
                $table->uuid('ci_templ_id');
                $table->primary('ci_templ_id'); 
                $table->string('ci_name', 50); 
                $table->BINARY('ci_type_id',16); 
				$table->string('prefix', 100);
				$table->string('variable_name', 100);
                $table->json('custom_attributes');
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_ci_templ_custom` MODIFY `ci_templ_id` BINARY(16);'); 
            DB::statement('ALTER TABLE `en_ci_templ_custom` MODIFY `ci_type_id` BINARY(16);'); 
            //DB::statement('ALTER TABLE `en_ci_templ_custom` MODIFY `custom_attributes` json()'); 
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('en_ci_templ_custom');
    }
}
