<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnReportModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_report_modules'))
        {
            Schema::create('en_report_modules', function (Blueprint $table) 
            {
                $table->uuid('module_id');
                $table->primary('module_id');
                $table->string('module_name',300)->nullable()->default(null);
                $table->string('module_key',300)->nullable()->default(null);
                $table->json('module_fields')->nullable()->default(null);
                $table->json('filter_fields')->nullable()->default(null);
                $table->json('date_filter_fields')->nullable()->default(null);
                $table->json('orignal_fields')->nullable()->default(null);
                $table->string('module_description',300)->nullable()->default(null);
                $table->enum('status',array('y', 'n', 'd'))->default('y');
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_report_modules` MODIFY `module_id` BINARY(16);'); 
        }
        if (Schema::hasTable('en_report_modules'))
        {
            Artisan::call( 'db:seed', ['--class' => 'EnReportModulesTableSeeder','--force' => true ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_report_modules');
    }
}
