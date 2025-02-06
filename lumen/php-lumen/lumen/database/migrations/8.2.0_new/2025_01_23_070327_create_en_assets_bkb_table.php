<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnAssetsBkbTable extends Migration
{
    public function up()
    {
        Schema::create('en_assets_bkb', function (Blueprint $table) {
            $table->binary('asset_id', 16)->primary();
            $table->string('asset_tag', 50)->collation('utf8mb4_unicode_ci');
            $table->string('display_name', 100)->collation('utf8mb4_unicode_ci');
            $table->binary('bv_id', 16)->nullable();
            $table->binary('department_id', 16)->nullable();
            $table->binary('po_id', 16)->nullable();
            $table->binary('location_id', 16)->nullable();
            $table->binary('parent_asset_id', 16)->nullable();
            $table->binary('object_id', 16)->nullable();
            $table->binary('ci_templ_id', 16)->nullable();
            $table->enum('ci_templ_type', ['default', 'custom'])->collation('utf8mb4_unicode_ci');
            $table->enum('asset_status', ['in_store', 'in_use', 'in_repair', 'expired'])->default('in_store')->collation('utf8mb4_unicode_ci');
            $table->enum('status', ['y', 'n', 'd'])->default('y')->collation('utf8mb4_unicode_ci');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('asset_sku', 255)->nullable()->collation('utf8mb4_unicode_ci');
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_assets_bkb');
    }
}
