<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;

class CreateEnComplaintRaised extends Migration

{
	/**
    * Run the migrations.
    *
    * @return void
    */
public function up()
{
    if (!Schema::hasTable('en_complaint_raised')) {
        Schema::create('en_complaint_raised', function (Blueprint $table) {
            $table->bigIncrements('cr_id');
            $table->string('complaint_raised_no', 150);
            $table->dateTime('complaint_raised_date');
            $table->binary('user_id', 16)->nullable();
            $table->binary('requester_id', 16);
            $table->binary('asset_id', 16);
            $table->string('priority', 50)->nullable();
            $table->text('problemdetail')->nullable();
            $table->text('attachment')->nullable();
            $table->binary('hod_id', 16)->nullable();
            $table->string('hod_remark', 255);
            $table->string('hod_status', 255)->nullable();
            $table->text('itfile')->nullable();
            $table->text('itstatus')->nullable();
            $table->string('it_remark', 255);
            $table->string('it_status', 120);
            $table->binary('vendor_id', 16);
            $table->text('storefile')->nullable();
            $table->string('store_remark', 255);
            $table->string('store_status', 120);
            $table->enum('status', ['HOD', 'IT', 'STORE'])->default('HOD');
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
        Schema::dropIfExists('en_complaint_raised');
    }


}
?>