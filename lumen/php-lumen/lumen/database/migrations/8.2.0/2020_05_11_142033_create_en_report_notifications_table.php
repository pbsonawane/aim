<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnReportNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_report_notifications'))
        {
            Schema::create('en_report_notifications', function (Blueprint $table) {
                $table->uuid('notification_id')->binary(16);
                $table->primary('notification_id');
                $table->uuid('report_id')->binary(16);
                $table->string('report_name')->nullable()->default(null);
                $table->string('export_type')->nullable()->default(null);
                $table->uuid('user_id')->binary(16);
                $table->enum('read', ['y','n'])->default('n');
                $table->timestamp('read_at')->nullable();
                $table->enum('status', ['y','n','d','q'])->default('q');
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_report_notifications` MODIFY `notification_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_report_notifications` MODIFY `report_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_report_notifications` MODIFY `user_id` BINARY(16);');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_report_notifications');
    }
}
