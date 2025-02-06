<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnSoftwareCategory extends Migration
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
									                $table->timestamps();            
											            });
				            
							if (Schema::hasTable('en_software_category')) {
								            if (Schema::hasColumn('en_software_category', 'software_category_id')) {
										                    DB::statement('ALTER TABLE `en_software_category` MODIFY `software_category_id` BINARY(16) ;');
												                 }
							}
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
