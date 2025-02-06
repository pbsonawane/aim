<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnSoftware extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_software'))
        {
            Schema::create('en_software', function (Blueprint $table)
            {
                $table->uuid('software_id');
                $table->primary('software_id');
                $table->integer('software_type_id')->unsigned();
                $table->integer('software_category_id')->unsigned();
                $table->integer('software_manufacturer_id')->unsigned();
                $table->integer('license_type_id')->unsigned();
                $table->string('description', 255);
                $table->string('ci_type', 255);
                $table->string('version', 255);
                $table->enum('status', array('y', 'n', 'd'))->default('y');
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_software` MODIFY `software_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software` MODIFY `software_type_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software` MODIFY `software_category_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software` MODIFY `software_manufacturer_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_software` MODIFY `license_type_id` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('en_software');
    }
}
