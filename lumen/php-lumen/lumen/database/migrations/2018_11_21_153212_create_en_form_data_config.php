<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnFormDataConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         if (!Schema::hasTable('en_form_data_config')){
            Schema::create('en_form_data_config', function (Blueprint $table){
                $table->uuid('config_id');
                $table->primary('config_id');

                $table->BINARY('form_templ_id', 16);
                $table->enum('form_templ_type',array('default', 'custom'))->default('default');
                $table->json('details');
                $table->enum('status',array('y', 'n'))->default('y');
                $table->timestamp("last_updated");
                $table->timestamp("date");
            });
            DB::statement('ALTER TABLE `en_form_data_config` MODIFY `config_id` BINARY(16);');
            DB::statement('ALTER TABLE `en_form_data_config` MODIFY `form_templ_id` BINARY(16);');

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('en_form_data_config');
    }

}
