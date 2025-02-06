<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnSoftwareManufacturer extends Migration
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
                $table->enum('is_default',array('y', 'n'))->default('n');
                $table->enum('env',array('development', 'production'))->default('development');	
                $table->timestamps();            
            });
            
			Schema::table('en_software_manufacturer', function (Blueprint $table) {
			DB::statement("ALTER TABLE `en_software_manufacturer` MODIFY `software_manufacturer_id` BINARY(16);");
        });
          
        }
		if (Schema::hasTable('en_software_manufacturer'))
        {
            Artisan::call( 'db:seed', ['--class' => 'EnSoftwareMakeSeeder','--force' => true ]);
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
