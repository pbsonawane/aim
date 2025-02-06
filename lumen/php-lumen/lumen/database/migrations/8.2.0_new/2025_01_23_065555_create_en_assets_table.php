<?php
 
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
 
class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('en_assets', function (Blueprint $table) {
            $table->binary('asset_id')->primary();
            $table->string('asset_tag', 50);
            $table->string('display_name', 100);
            $table->string('asset_unit', 50)->nullable();
            $table->binary('bv_id')->nullable();
            $table->binary('department_id')->nullable();
            $table->binary('po_id')->nullable();
            $table->binary('location_id')->nullable();
            $table->binary('parent_asset_id')->nullable();
            $table->binary('object_id')->nullable();
            $table->binary('ci_templ_id')->nullable();
            $table->enum('ci_templ_type', ['default', 'custom']);
            $table->enum('asset_status', ['in_store', 'in_use', 'in_repair', 'expired'])->default('in_store');
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('asset_sku')->nullable(); // Define asset_sku if needed
        });
    }
 
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
}