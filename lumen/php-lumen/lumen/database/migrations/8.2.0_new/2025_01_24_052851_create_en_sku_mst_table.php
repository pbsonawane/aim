<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_sku_mst', function (Blueprint $table) {
            $table->id();
            $table->string('sku_code', 255)->nullable();
            $table->string('sku_code_id', 255)->nullable();
            $table->bigInteger('core_product_id')->nullable();
            $table->string('core_product_name', 255)->nullable();
            $table->text('coreproduct_description')->nullable();
            $table->bigInteger('primary_category_id')->nullable();
            $table->string('primary_category_name', 255)->nullable();
            $table->string('primary_category_abbreviation', 50)->nullable();
            $table->bigInteger('secondary_category_id')->nullable();
            $table->string('secondary_category_name', 255)->nullable();
            $table->string('secondary_category_abbreviation', 50)->nullable();
            $table->bigInteger('tertiary_category_id')->nullable();
            $table->string('tertiary_category_name', 255)->nullable();
            $table->string('tertiary_category_abbreviation', 50)->nullable();
            $table->bigInteger('fourth_category_id')->nullable();
            $table->string('fourth_category_name', 255)->nullable();
            $table->string('fourth_category_abbreviation', 50)->nullable();
            $table->bigInteger('fifth_category_id')->nullable();
            $table->string('fifth_category_name', 255)->nullable();
            $table->string('fifth_category_abbreviation', 50)->nullable();
            $table->bigInteger('measurement_unit_id')->nullable();
            $table->string('measurement_unit_name', 255)->nullable();
            $table->string('measurement_unit_code', 255)->nullable();
            $table->dateTime('crm_created_dt')->nullable();
            $table->dateTime('crm_updated_dt')->nullable();
            $table->dateTime('created_at')->useCurrent()->nullable();
            $table->dateTime('updated_at')->useCurrent()->nullable();
            $table->enum('is_added_by_cron', ['0', '1'])->default('0');
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_sku_mst');
    }
};
