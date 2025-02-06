<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EnLicenseTypeRenameColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       
             Schema::table('en_license_type', function (Blueprint $table) {
           
                    DB::statement("ALTER TABLE en_license_type CHANGE en_license_type_id license_type_id BINARY(16);");
                });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
