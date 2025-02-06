<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;

class CreateEnSkuMst extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_sku_mst')) {
            Schema::create('en_sku_mst', function (Blueprint $table) {
                $table->bigIncrements('id'); // Auto increment primary key
                $table->char('sku_code', 255)->nullable();
                $table->char('sku_code_id', 255)->nullable();
                $table->bigInteger('core_product_id')->nullable()->unsigned();
                $table->char('core_product_name', 255)->nullable();
                $table->text('coreproduct_description')->nullable();
                $table->bigInteger('primary_category_id')->nullable()->unsigned();
                $table->char('primary_category_name', 255)->nullable();
                $table->char('primary_category_abbreviation', 50)->nullable();
                $table->bigInteger('secondary_category_id')->nullable()->unsigned();
                $table->char('secondary_category_name', 255)->nullable();
                $table->char('secondary_category_abbreviation', 50)->nullable();
                $table->bigInteger('tertiary_category_id')->nullable()->unsigned();
                $table->char('tertiary_category_name', 255)->nullable();
                $table->char('tertiary_category_abbreviation', 50)->nullable();
                $table->bigInteger('fourth_category_id')->nullable()->unsigned();
                $table->char('fourth_category_name', 255)->nullable();
                $table->char('fourth_category_abbreviation', 50)->nullable();
                $table->bigInteger('fifth_category_id')->nullable()->unsigned();
                $table->char('fifth_category_name', 255)->nullable();
                $table->char('fifth_category_abbreviation', 50)->nullable();
                $table->bigInteger('measurement_unit_id')->nullable()->unsigned();
                $table->char('measurement_unit_name', 255)->nullable();
                $table->char('measurement_unit_code', 255)->nullable();
                $table->timestamp('crm_created_dt')->nullable();
                $table->timestamp('crm_updated_dt')->nullable();
                $table->timestamps(); // Automatically adds 'created_at' and 'updated_at' columns
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_sku_mst');
    }
}
?>
