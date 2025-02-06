<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
                $table->string('report_name')->nullable()->default(null);    
                $table->string('report_category')->nullable()->default(null);
                $table->string('report_title')->nullable()->default(null);
                $table->string('module')->nullable()->default(null);
                $table->text('filter_value')->nullable()->default(null);
                $table->string('user_id')->nullable()->default(null);
                $table->enum('share_report', ['y', 'n'])->default('n');    
                $table->string('schedule_type')->nullable()->default(null);
                $table->string('gen_report_at')->nullable()->default(null);
                $table->string('gen_report_for')->nullable()->default(null);
                $table->string('report_format')->nullable()->default(null);
                $table->string('email_to')->nullable()->default(null);
                $table->string('email_subject')->nullable()->default(null);
                $table->text('email_body')->nullable()->default(null);
                $table->timestamp('next_report_time')->nullable()->default(null);
                $table->enum('enableschedule', ['y', 'n'])->default('y');    
                $table->enum('status', ['y','n','d','q'])->default('y');
                $table->timestamps();

            });
            DB::statement('ALTER TABLE `en_reports` MODIFY `report_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_reports` MODIFY `report_cat_id` BINARY(16);');
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
