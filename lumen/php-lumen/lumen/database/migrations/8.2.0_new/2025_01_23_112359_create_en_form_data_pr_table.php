<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_form_data_pr', function (Blueprint $table) {
            $table->binary('pr_id',16)->primary();
            $table->string('pr_no', 255);
            $table->string('estimate_status', 50);
            $table->double('estimate_cost', 10, 2);
            $table->string('estimate_cost_comment', 255);
            $table->binary('form_templ_id',16)->nullable();
            $table->enum('form_templ_type', ['default', 'custom'])->default('default');
            $table->json('details');
            $table->json('asset_details');
            $table->json('approval_details');
            $table->json('approved_status');
            $table->enum('approval_req', ['y', 'n'])->default('n');
            $table->binary('bv_id',16)->nullable();
            $table->binary('dc_id',16)->nullable();
            $table->binary('location_id',16)->nullable();
            $table->binary('assignpr_user_id',16)->nullable();
            $table->json('remark');
            $table->enum('status', [
                'pending approval', 'open', 'partially approved', 
                'approved', 'closed', 'cancelled', 'deleted', 'rejected'
            ])->default('open');
            $table->binary('requester_id',16)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_form_data_po');
    }
};
