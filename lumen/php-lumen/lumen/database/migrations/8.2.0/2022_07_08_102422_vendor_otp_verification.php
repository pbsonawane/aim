<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VendorOtpVerification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('vendor_otp_verification')) {
            Schema::create('vendor_otp_verification', function (Blueprint $table) {
                $table->uuid('id');
                $table->primary('id');
                $table->string('email',255);
                $table->string('otp',5);
                $table->string('token',255);
              //  $table->dateTime('created_date');
                $table->timestamps();
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
        //
        Schema::dropIfExists('vendor_otp_verification');
    }
}
