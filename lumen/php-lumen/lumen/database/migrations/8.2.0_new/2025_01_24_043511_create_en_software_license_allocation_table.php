<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_software_license_allocation', function (Blueprint $table) {
            $table->binary('sw_license_allocation_id');
            $table->binary('software_id')->nullable();
            $table->binary('software_license_id')->nullable();
            $table->longText('asset_id');
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary('sw_license_allocation_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_software_license_allocation');
    }
};
