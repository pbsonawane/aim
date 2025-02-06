<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnSoftwareCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if (!Schema::hasTable('en_software_category')){
		                Schema::create('en_software_category', function (Blueprint $table){
							$table->uuid('software_category_id');
							$table->primary('software_category_id'); 
							$table->string('software_category', 100); 
							$table->string('description', 255); 
							$table->enum('env',array('development', 'production'))->default('development'); 
							$table->enum('status',array('y', 'n', 'd'))->default('y');
                			$table->enum('is_default',array('y', 'n'))->default('n');
							$table->timestamps();            
						});
				            
						if (Schema::hasTable('en_software_category')) {
						     if (Schema::hasColumn('en_software_category', 'software_category_id')) {
						       DB::statement('ALTER TABLE `en_software_category` MODIFY `software_category_id` BINARY(16) ;');
							 }
						}
				} 
				if (Schema::hasTable('en_software_category'))
				{
					Artisan::call( 'db:seed', ['--class' => 'EnSoftwareCategorySeeder','--force' => true ]);
				}
						
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
          Schema::dropIfExists('en_software_category');
    }
}
