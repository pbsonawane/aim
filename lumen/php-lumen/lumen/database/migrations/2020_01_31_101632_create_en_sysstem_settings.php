<?php

 

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

 

class CreateEnSysstemSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_system_settings')) { 
            Schema::create('en_system_settings', function (Blueprint $table) {
               $table->increments('setting_id');
               $table->text('configuration');
               $table->enum('status',array('y', 'n'))->default('y');
               $table->enum('type',array('ensysconfig'));
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
        Schema::drop('en_system_settings');
    }
}