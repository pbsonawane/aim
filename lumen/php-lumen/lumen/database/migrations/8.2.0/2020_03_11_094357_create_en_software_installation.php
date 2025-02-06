<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;

class CreateEnSoftwareInstallation extends Migration
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
                $table->BINARY('software_id',16);
				$table->json('asset_id'); 
				$table->enum('status',array('y', 'n','d'))->default('y');				
                $table->timestamps();            
            });
            DB::statement("ALTER TABLE `en_software_installation` MODIFY `sw_install_id` BINARY(16);");
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
