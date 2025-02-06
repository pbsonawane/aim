<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_user_notification', function (Blueprint $table) {
            $table->id(); // Primary key 'id' with AUTO_INCREMENT
            $table->string('type', 255)->nullable();
            $table->text('message')->nullable();
            $table->binary('store_user')->nullable();
            $table->binary('show_user')->nullable();
            $table->enum('notification_read', ['y', 'n'])->default('n');
            $table->timestamp('read_at')->useCurrent(); // Default to CURRENT_TIMESTAMP
            $table->enum('status', ['y', 'n', 'd', 'q'])->default('q');
          $table->timestamp('created_at')->nullable(); // Created timestamp
            $table->timestamp('updated_at')->nullable(); // Updated timestamp
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_user_notification');
    }
};
