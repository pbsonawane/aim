<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnSoftwareCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('en_software_category', function (Blueprint $table) {
            $table->binary('software_category_id')->primary();
            $table->string('software_category', 100);
            $table->string('description', 255);
            $table->enum('env', ['development', 'production'])->default('development');
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->enum('is_default', ['y', 'n'])->default('n');
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
