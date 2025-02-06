<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;

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
                $table->enum('is_default',array('y', 'n'))->default('n');
                $table->timestamps();            
            });
            DB::statement('ALTER TABLE `en_relationship_type` MODIFY `rel_type_id` BINARY(16);'); 
        }
		
		if (Schema::hasTable('en_relationship_type'))
        {
            Artisan::call( 'db:seed', ['--class' => 'EnRelationshipTypeSeeder','--force' => true ]);
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
