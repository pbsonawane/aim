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
        Schema::create('en_ci_quotation_comparison', function (Blueprint $table) {
            $table->binary('quotation_cmp_id', 16)->primary();
            $table->binary('pr_po_id', 16)->nullable();
            $table->binary('selected_item_id', 16)->nullable();
            $table->string('selected_item_name', 255)->collation('utf8mb4_unicode_ci');
            $table->longText('quotation_comparison_data')->collation('utf8mb4_bin');
            $table->longText('vendor_approve')->collation('utf8mb4_bin');
            $table->enum('approval', ['approved', 'rejected'])->nullable()->collation('utf8mb4_unicode_ci');
            $table->text('reject_comment')->collation('utf8mb4_unicode_ci');
            $table->binary('approve_reject_by', 16)->nullable();
            $table->enum('status', ['y', 'n', 'd'])->default('y')->collation('utf8mb4_unicode_ci');
            $table->binary('created_by', 16);
            $table->timestamps();
            $table->binary('updated_by', 16)->nullable();
            $table->binary('approve_vendor_id', 16)->nullable();
            $table->string('approve_option', 5)->collation('utf8mb4_unicode_ci');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_ci_quotation_comparison');
    }
};
