<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;

class CreateEnUserNotification extends Migration
{
	public function up()
{
    if (!Schema::hasTable('en_user_notification')) {
        Schema::create('en_user_notification', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key with auto-increment
            $table->string('type', 255)->nullable(); // Nullable varchar(255) for type
            $table->text('message')->nullable(); // Nullable text for message
            $table->binary('store_user')->nullable(); // Nullable binary(16) for store_user
            $table->binary('show_user')->nullable(); // Nullable binary(16) for show_user
            $table->enum('notification_read', ['y', 'n'])->default('n'); // Enum for notification_read with default 'n'
            $table->dateTime('read_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Default current timestamp for read_at
            $table->enum('status', ['y', 'n', 'd', 'q'])->default('q'); // Enum for status with default 'q'
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Default current timestamp for created_at
            $table->dateTime('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate(); // Default current timestamp for updated_at with on update current timestamp
        });
    }
}
     /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_user_notification');
    }

}







?>