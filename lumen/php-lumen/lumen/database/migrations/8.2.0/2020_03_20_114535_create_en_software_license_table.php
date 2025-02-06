<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnSoftwareLicenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_software_license'))
        {
            Schema::create('en_software_license', function (Blueprint $table)
            {
                $table->uuid('software_license_id');
                $table->primary('software_license_id');
				$table->integer('software_id')->unsigned();
                $table->integer('software_manufacturer_id')->unsigned();
                $table->integer('license_type_id')->unsigned();
				$table->string('license_key', 100);
                $table->integer('vendor_id')->unsigned();
                $table->integer('department_id')->unsigned();
                $table->integer('location_id')->unsigned();
                $table->integer('bv_id')->unsigned();
                $table->string('max_installation', 255);
                $table->string('purchase_cost', 255);
                $table->string('description', 255);
                $table->date('acquisition_date');
                $table->date('expiry_date');
                $table->enum('status', array('y', 'n', 'd'))->default('y');
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_software_license` MODIFY `software_license_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software_license` MODIFY `software_manufacturer_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software_license` MODIFY `license_type_id` BINARY(16);');
			DB::statement('ALTER TABLE `en_software_license` MODIFY `software_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software_license` MODIFY `vendor_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software_license` MODIFY `department_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software_license` MODIFY `location_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software_license` MODIFY `bv_id` BINARY(16);');

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_software_license');
    }
}
