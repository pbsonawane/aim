<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_software_types', function (Blueprint $table) {
            $table->binary('software_type_id');
            $table->string('software_type', 100);
            $table->string('description', 255);
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->enum('is_default', ['y', 'n'])->default('n');
            $table->enum('env', ['development', 'production'])->default('development');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary('software_type_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_software_types');
    }
};
