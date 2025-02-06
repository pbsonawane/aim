<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_complaint_raised', function (Blueprint $table) {
            $table->id('cr_id');
            $table->string('complaint_raised_no', 150);
            $table->dateTime('complaint_raised_date');
            $table->binary('user_id')->nullable();
            $table->binary('requester_id');
            $table->binary('asset_id');
            $table->string('priority', 50)->nullable();
            $table->text('problemdetail')->nullable();
            $table->text('attachment')->nullable();
            $table->binary('hod_id')->nullable();
            $table->string('hod_remark', 255);
            $table->string('hod_status', 255)->nullable();
            $table->text('itfile')->nullable();
            $table->text('itstatus')->nullable();
            $table->string('it_remark', 255);
            $table->string('it_status', 120);
            $table->binary('vendor_id');
            $table->text('storefile')->nullable();
            $table->string('store_remark', 255);
            $table->string('store_status', 120);
            $table->enum('status', ['HOD', 'IT', 'STORE'])->default('HOD');
            
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
        });
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
};
