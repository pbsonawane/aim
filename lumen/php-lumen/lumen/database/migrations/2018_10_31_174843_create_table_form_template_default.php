<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFormTemplateDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('form_template_default')) {
                        Schema::create('form_template_default', function (Blueprint $table) {
                                $table->uuid('form_templ_id');
                                $table->string("template_name",255);
                                $table->string("template_title",255);
                                $table->enum("type",array('po','warrenty','service','cr','incident','problem','config'))->default("config")->nullable(false);
                                $table->text("details");
                                $table->enum("default_template",array("y","n"))->default("n");
                                $table->enum("status",array("y","n","d"))->default("y");
                                $table->timestamp("last_updated");
                        });
                }
                DB::statement('ALTER TABLE `form_template_default` MODIFY `form_templ_id` BINARY(16) ;');
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('form_template_default');
    }
}
