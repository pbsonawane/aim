<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnSoftwareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_software', function (Blueprint $table) {
            $table->binary('software_id',16)->primary();
            $table->string('software_name', 255);
            $table->binary('software_type_id',16)->nullable();
            $table->binary('software_category_id',16)->nullable();
            $table->binary('software_manufacturer_id',16)->nullable();
            $table->binary('license_type_id',16)->nullable();
            $table->string('description', 255);
            $table->string('ci_type', 255);
            $table->string('version', 255);
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_software');
    }
}
