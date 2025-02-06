<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_system_settings', function (Blueprint $table) {
            $table->increments('setting_id'); // Unsigned AUTO_INCREMENT primary key
            $table->text('configuration'); // Text column
            $table->enum('status', ['y', 'n'])->default('y'); // Enum column with default value
            $table->char('type', 30)->nullable(); // Optional char column
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_system_settings');
    }
};
