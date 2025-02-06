<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_software_license', function (Blueprint $table) {
            $table->binary('software_license_id');
            $table->binary('software_id')->nullable();
            $table->binary('software_manufacturer_id')->nullable();
            $table->binary('license_type_id')->nullable();
            $table->string('license_key', 100);
            $table->binary('vendor_id')->nullable();
            $table->binary('department_id')->nullable();
            $table->binary('location_id')->nullable();
            $table->binary('bv_id')->nullable();
            $table->string('max_installation', 255);
            $table->string('purchase_cost', 255);
            $table->string('description', 255);
            $table->date('acquisition_date');
            $table->date('expiry_date');
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary('software_license_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_software_license');
    }
};
