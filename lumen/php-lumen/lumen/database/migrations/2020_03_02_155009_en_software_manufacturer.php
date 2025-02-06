<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnSoftwareManufacturer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_software_manufacturer')){
            Schema::create('en_software_manufacturer', function (Blueprint $table){
                $table->uuid('software_manufacturer_id');
                $table->primary('software_manufacturer_id'); 
                $table->string('software_manufacturer', 100); 
                $table->string('description', 255); 
				$table->enum('status',array('y', 'n','d'))->default('y');	
                $table->timestamps();            
            });
            
			Schema::table('en_software_manufacturer', function (Blueprint $table) {
			DB::statement("ALTER TABLE `en_software_manufacturer` MODIFY `software_manufacturer_id` BINARY(16);");
        });
          
        } 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_software_manufacturer');
    }
}
