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
        Schema::create('en_asset_history', function (Blueprint $table) {
            $table->binary('id', 16)->primary();
            $table->binary('asset_id', 16)->nullable();
            $table->binary('user_id', 16)->nullable();
            $table->string('action', 50)->collation('utf8mb4_unicode_ci');
            $table->text('message')->collation('utf8mb4_unicode_ci');
            $table->text('comment')->collation('utf8mb4_unicode_ci');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_asset_history');
    }
};
