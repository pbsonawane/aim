<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_template_datastore', function (Blueprint $table) {
            $table->binary('datastore_id')->nullable();
            $table->string('type', 100);
            $table->string('name', 255);
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_template_datastore');
    }
};
