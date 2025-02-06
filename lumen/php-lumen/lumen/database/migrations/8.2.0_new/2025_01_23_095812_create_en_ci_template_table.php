<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_ci_template', function (Blueprint $table) {
            $table->bigIncrements('ci_id'); // Primary key with auto-increment
            $table->char('ci_name', 50)->collation('utf8mb4_unicode_ci')->nullable();
            $table->unsignedTinyInteger('ci_type_id')->nullable();
            $table->text('ci_template_content')->collation('utf8mb4_unicode_ci');
            $table->enum('status', ['y', 'n'])->collation('utf8mb4_unicode_ci')->nullable();
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_ci_template');
    }
};
