<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_software_installation', function (Blueprint $table) {
            $table->binary('sw_install_id');
            $table->binary('software_id');
            $table->longText('asset_id');
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary('sw_install_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_software_installation');
    }
};
