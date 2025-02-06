<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnSoftwareInstallation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_software_installation')){
            Schema::create('en_software_installation', function (Blueprint $table){
                $table->uuid('sw_install_id');
                $table->primary('sw_install_id');
				$table->json('asset_id'); 
				$table->enum('status',array('y', 'n','d'))->default('y');				
                $table->timestamps();            
            });
            
			Schema::table('en_software_installation', function (Blueprint $table) {
           
			DB::statement("ALTER TABLE `en_software_installation` MODIFY `sw_install_id` BINARY(16);");
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
        Schema::dropIfExists('en_software_installation');
    }
}
