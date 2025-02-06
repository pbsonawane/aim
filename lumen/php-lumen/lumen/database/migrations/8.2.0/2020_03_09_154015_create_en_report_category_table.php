<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnReportCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_report_category'))
            {
                Schema::create('en_report_category', function (Blueprint $table){
                    $table->uuid('report_cat_id');
                    $table->primary('report_cat_id'); 
                    $table->string('report_category', 255);
                    $table->text('description'); 
                    $table->enum('status',array('y', 'n', 'd'))->default('y');   
                    $table->timestamps();            
                });
                DB::statement('ALTER TABLE `en_report_category` MODIFY `report_cat_id` BINARY(16);'); 
            }
        if (Schema::hasTable('en_report_category'))
        {
            Artisan::call( 'db:seed', ['--class' => 'EnReportCategoryTableSeeder','--force' => true ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_report_category');
    }
}
