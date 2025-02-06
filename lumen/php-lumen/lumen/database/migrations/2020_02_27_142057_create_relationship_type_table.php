<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelationshipTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_relationship_type')){
            Schema::create('en_relationship_type', function (Blueprint $table){
                $table->uuid('rel_type_id');
                $table->primary('rel_type_id'); 
                $table->string('rel_type', 255); 
                $table->string('inverse_rel_type', 255); 
                $table->string('description', 255); 
                $table->enum('status',array('y', 'n', 'd'))->default('y');   
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_relationship_type` MODIFY `rel_type_id` BINARY(16);'); 
        }
    }  

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_relationship_type');
    }
}
