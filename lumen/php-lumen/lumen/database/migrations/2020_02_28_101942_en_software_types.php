<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnSoftwareTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if (!Schema::hasTable('en_software_types')){
		                Schema::create('en_software_types', function (Blueprint $table){
					                $table->uuid('software_type_id');
							                $table->primary('software_type_id'); 
							                $table->string('software_type', 100); 
									                $table->string('description', 255); 
													$table->enum('status',array('y', 'n','d'))->default('y');
									                $table->timestamps();            
											            });
				            

								           
										
				if (Schema::hasTable('en_software_types')) {
			            if (Schema::hasColumn('en_software_types', 'software_type_id')) {
				                    DB::statement('ALTER TABLE `en_software_types` MODIFY `software_type_id` BINARY(16) ;');
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
	    Schema::dropIfExists('en_software_types');
    }
}
