<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;

class CreateEnSoftwareLicenseAllocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_software_license_allocation'))
        {
            Schema::create('en_software_license_allocation', function (Blueprint $table)
            {
                $table->uuid('sw_license_allocation_id');
                $table->primary('sw_license_allocation_id');
                $table->integer('software_id')->unsigned();
                $table->integer('software_license_id')->unsigned();
                $table->json('asset_id'); 
                $table->enum('status', array('y', 'n', 'd'))->default('y');
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_software_license_allocation` MODIFY `sw_license_allocation_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software_license_allocation` MODIFY `software_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software_license_allocation` MODIFY `software_license_id` BINARY(16);');
            
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_software_license_allocation');
    }
}
