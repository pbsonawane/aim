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
        Schema::create('en_pr_po_asset_details', function (Blueprint $table) {
            $table->binary('pr_po_asset_id'); // Primary Key (can also use $table->primary('pr_po_asset_id') if it's the primary key)
            $table->binary('pr_po_id');
            $table->enum('asset_type', ['pr', 'po']);
            $table->json('asset_details');
            $table->json('vendor_approval')->nullable();
            $table->binary('created_by');
            $table->enum('status', ['y', 'n', 'd'])->default('y');
            $table->enum('convert_status', ['n', 'y'])->default('n');
            $table->enum('assign_status', ['n', 'y'])->default('n');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_pr_po_asset_details');
    }
};
