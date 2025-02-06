<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnCiTemplmCustfieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_ci_templ_custfields')){
            Schema::create('en_ci_templ_custfields', function (Blueprint $table){
                $table->uuid('ci_custfield_id');
                $table->primary('ci_custfield_id'); 
                $table->BINARY('ci_templ_id', 16); 
                $table->json('custom_attributes');
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
             DB::statement('ALTER TABLE `en_ci_templ_custfields` MODIFY `ci_custfield_id` BINARY(16);'); 
             DB::statement('ALTER TABLE `en_ci_templ_custfields` MODIFY `ci_templ_id` BINARY(16);'); 
            // DB::statement('ALTER TABLE `en_ci_templ_custfields` MODIFY `custom_attributes` json()'); 
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('en_ci_templ_custfields');
    }
}
