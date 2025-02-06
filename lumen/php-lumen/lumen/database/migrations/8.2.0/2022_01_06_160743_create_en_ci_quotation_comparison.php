<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnCiQuotationComparison extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('en_ci_quotation_comparison')) {
            Schema::create('en_ci_quotation_comparison', function (Blueprint $table) {
                $table->uuid('quotation_cmp_id');
                $table->primary('quotation_cmp_id');
                $table->BINARY('selected_item_id', 16);
                $table->string('selected_item_name');
                $table->json('quotation_comparison_data');
                $table->json('vendor_approve');
                $table->enum('approval', array('approved', 'rejected'));
                $table->string('reject_comment');
                $table->BINARY('approve_reject_by', 16);
                $table->enum('status', array('y', 'n', 'd'))->default('y');
                $table->BINARY('created_by', 16);
                $table->BINARY('updated_by', 16);
                $table->BINARY('approve_vendor_id', 16);
                $table->string('approve_option');
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_ci_quotation_comparison` MODIFY `quotation_cmp_id` BINARY(16);');
        }
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
}