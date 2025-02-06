<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnImportNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_import_notifications'))
        {
            Schema::create('en_import_notifications', function (Blueprint $table) {
                $table->uuid('notification_id')->binary(16);
                $table->primary('notification_id');
                $table->string('import_name')->nullable()->default(null);
                $table->uuid('user_id')->binary(16);
                $table->string('filename')->nullable()->default(null);
                $table->json('importdata')->nullable()->default(null);
                $table->json('result')->nullable()->default(null);
                $table->enum('read', ['y','n'])->default('n');
                $table->timestamp('read_at')->nullable();
                $table->enum('status', ['y','n','d','q'])->default('q');
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_import_notifications` MODIFY `notification_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_import_notifications` MODIFY `user_id` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_import_notifications');
    }
}
