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
        Schema::create('en_asset_relationship', function (Blueprint $table) {
            $table->binary('asset_relationship_id', 16)->primary();
            $table->binary('parent_asset_id', 16)->nullable();
            $table->binary('child_asset_id', 16)->nullable();
            $table->binary('rel_type_id', 16)->nullable();
            $table->string('ci_templ_id', 100)->collation('utf8mb4_unicode_ci');
            $table->enum('status', ['y', 'n', 'd'])->default('y')->collation('utf8mb4_unicode_ci');
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
        Schema::dropIfExists('en_asset_relationship');
    }
};
