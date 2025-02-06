<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnLicenseType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_license_type')){
            Schema::create('en_license_type', function (Blueprint $table){
                $table->uuid('en_license_type_id');
                $table->primary('en_license_type_id'); 
                $table->string('license_type', 100); 
				$table->enum('installation_allow',array('Single', 'Volume', 'Unlimited', 'OEM'))->default('Unlimited');
				$table->enum('is_perpetual',array('y', 'n'))->default('y');
				$table->enum('is_free',array('y', 'n'))->default('y');
				$table->enum('status',array('y', 'n','d'))->default('y');				
                $table->timestamps();            
            });
            
			Schema::table('en_license_type', function (Blueprint $table) {
           
			DB::statement("ALTER TABLE `en_license_type` MODIFY `en_license_type_id` BINARY(16);");
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
        Schema::dropIfExists('en_license_type');
    }
}
