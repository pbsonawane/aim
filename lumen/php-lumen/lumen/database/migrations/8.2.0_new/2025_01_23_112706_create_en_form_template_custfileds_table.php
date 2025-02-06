<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_form_template_custfileds', function (Blueprint $table) {
            $table->binary('form_templ_id');
            $table->longText('custom_fields');
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->timestamp('last_updated')->useCurrent()->onUpdate(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_form_template_custfileds');
    }
};
