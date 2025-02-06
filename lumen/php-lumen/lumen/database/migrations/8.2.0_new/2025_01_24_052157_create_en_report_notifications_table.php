<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_report_notifications', function (Blueprint $table) {
            $table->binary('notification_id', 16);
            $table->binary('report_id', 16)->nullable();
            $table->string('report_name', 255)->nullable();
            $table->string('export_type', 255)->nullable();
            $table->binary('user_id', 16)->nullable();
            $table->enum('read', ['y', 'n'])->default('n');
            $table->timestamp('read_at')->nullable();
            $table->enum('status', ['y', 'n', 'd', 'q'])->default('q');
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_report_notifications');
    }
};
