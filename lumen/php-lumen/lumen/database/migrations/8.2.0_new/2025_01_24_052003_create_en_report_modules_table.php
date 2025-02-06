<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_report_modules', function (Blueprint $table) {
            $table->id();
            $table->binary('module_id', 16);
            $table->string('module_name', 300)->nullable();
            $table->string('module_key', 300)->nullable();
            $table->longText('module_fields')->nullable();
            $table->longText('filter_fields')->nullable();
            $table->longText('date_filter_fields')->nullable();
            $table->longText('orignal_fields')->nullable();
            $table->string('module_description', 300)->nullable();
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->timestamp('created_at')->nullable(); // Created timestamp
            $table->timestamp('updated_at')->nullable(); // Updated timestamp
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_report_modules');
    }
};
