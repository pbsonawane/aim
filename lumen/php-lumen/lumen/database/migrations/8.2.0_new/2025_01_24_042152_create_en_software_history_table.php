<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_software_history', function (Blueprint $table) {
            $table->binary('id');
            $table->binary('software_id')->nullable();
            $table->binary('user_id')->nullable();
            $table->string('action', 50);
            $table->text('message');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->primary('id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_software_history');
    }
};
