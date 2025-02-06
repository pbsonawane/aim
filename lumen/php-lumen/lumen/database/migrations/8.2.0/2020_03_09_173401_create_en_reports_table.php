<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_reports'))
        {
            Schema::create('en_reports', function (Blueprint $table) 
            {
                $table->uuid('report_id')->binary(16);
                $table->primary('report_id'); 
                $table->uuid('report_cat_id')->binary(16);
                $table->uuid('user_id')->binary(16);
                $table->string('report_name')->nullable()->default(null);    
                $table->string('module')->nullable()->default(null);
                $table->json('filter_fields')->nullable()->default(null);
                $table->json('details')->nullable()->default(null);
                $table->string('filter_date_field')->nullable()->default(null);
                $table->string('filter_date_value')->nullable()->default(null);
                $table->string('filter_date_range')->nullable()->default(null);
                $table->json('filters')->nullable()->default(null);
                $table->enum('share_report', ['y', 'n'])->default('n');    
                $table->string('schedule_type')->nullable()->default(null);
                $table->string('gen_report_at')->nullable()->default(null);
                $table->string('gen_report_for')->nullable()->default(null);
                $table->string('report_format')->nullable()->default(null);
                $table->string('email_to')->nullable()->default(null);
                $table->string('email_subject')->nullable()->default(null);
                $table->text('email_body')->nullable()->default(null);
                $table->timestamp('next_report_time')->nullable()->default(null);
                $table->enum('enableschedule', ['y', 'n'])->default('n');    
                $table->enum('status', ['y','n','d','q'])->default('y');
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_reports` MODIFY `report_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_reports` MODIFY `report_cat_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_reports` MODIFY `user_id` BINARY(16);');
        }
        if (Schema::hasTable('en_reports'))
        {
            Artisan::call( 'db:seed', ['--class' => 'EnReportsTableSeeder','--force' => true ]);
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_reports');
    }
}