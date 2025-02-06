<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnEmailTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_email_template', function (Blueprint $table) {
            $table->uuid('template_id');
            $table->primary('template_id');
            $table->string('template_name',100); 
            $table->string('template_key', 100);
            $table->string('template_category', 100); 
            $table->enum('configure_email_id', array('y', 'n'))->default('n');
            $table->string('email_ids', 500)->nullable();
            $table->text('email_body');
            $table->enum('status',array('e', 'd'))->default('d');    
            $table->timestamps();          
        });
        DB::statement('ALTER TABLE `en_email_template` MODIFY `template_id` BINARY(16);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_email_template');
    }
}
