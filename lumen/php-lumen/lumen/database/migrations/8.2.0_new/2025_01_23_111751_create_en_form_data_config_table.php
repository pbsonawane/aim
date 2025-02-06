<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_form_data_config', function (Blueprint $table) {
            $table->id();
            $table->binary('config_id');
            $table->binary('form_templ_id')->nullable();
            $table->enum('form_templ_type', ['default', 'custom'])->default('default');
            $table->longText('details');
            $table->enum('status', ['y', 'n'])->default('y');
            $table->timestamp('last_updated')->useCurrent()->onUpdate(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_form_data_config');
    }
};
