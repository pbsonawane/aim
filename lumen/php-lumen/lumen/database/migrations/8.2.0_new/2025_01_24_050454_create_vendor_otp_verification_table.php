<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vendor_otp_verification', function (Blueprint $table) {
            $table->id(); 
            $table->string('email', 255); 
            $table->char('otp', 50); 
            $table->string('token', 255); 
            $table->timestamp('created_date')->useCurrent(); 
            $table->timestamps(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_otp_verification');
    }
};
