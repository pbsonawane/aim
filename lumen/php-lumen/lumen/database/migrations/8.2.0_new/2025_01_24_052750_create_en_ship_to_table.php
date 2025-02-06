<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_ship_to', function (Blueprint $table) {
            $table->binary('shipto_id', 16);
            $table->binary('locations', 16)->nullable();
            $table->string('company_name', 255);
            $table->text('address');
            $table->string('pan_no', 10);
            $table->string('gstn', 20);
            $table->enum('status', ['y', 'n', 'd'])->default('y');
          $table->timestamp('created_at')->nullable(); // Created timestamp
            $table->timestamp('updated_at')->nullable(); // Updated timestamp
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_ship_to');
    }
};
