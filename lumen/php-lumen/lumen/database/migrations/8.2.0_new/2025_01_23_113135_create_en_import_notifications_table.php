<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_import_notifications', function (Blueprint $table) {
            $table->binary('notification_id');
            $table->string('import_name')->nullable();
            $table->binary('user_id')->nullable();
            $table->string('filename')->nullable();
            $table->longText('importdata')->nullable();
            $table->longText('result')->nullable();
            $table->enum('read', ['y', 'n'])->default('n');
            $table->timestamp('read_at')->nullable();
            $table->enum('status', ['y', 'n', 'd', 'q'])->default('q');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_import_notifications');
    }
};
