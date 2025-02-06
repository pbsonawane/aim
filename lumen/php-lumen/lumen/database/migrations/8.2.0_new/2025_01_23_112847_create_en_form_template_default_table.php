<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_form_template_default', function (Blueprint $table) {
            $table->binary('form_templ_id');
            $table->string('template_name');
            $table->string('template_title');
            $table->enum('type', ['po', 'warranty', 'service', 'cr', 'incident', 'other'])->default('config');
            $table->string('description');
            $table->enum('default_template', ['y', 'n'])->default('n');
            $table->longText('details');
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->timestamp('last_updated')->useCurrent()->onUpdate(DB::raw('CURRENT_TIMESTAMP'));
           
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_form_template_default');
    }
};
