<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFormTemplateCustfileds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('form_template_custfileds')) {
                        Schema::create('form_template_custfileds', function (Blueprint $table) {
                                $table->uuid('form_templ_id');
                                $table->primary('form_templ_id'); 
                                $table->text("custom_fields");
                                $table->enum("status",array("y","n","d"))->default("y");
                                $table->timestamp("last_updated");
                        });
                }
                DB::statement('ALTER TABLE `form_template_custfileds` MODIFY `form_templ_id` BINARY(16) ;');
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('form_template_custfileds');
    }
}
