<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('en_form_data_po', function (Blueprint $table) {
            $table->binary('po_id');
            $table->binary('pr_id')->nullable();
            $table->binary('form_templ_id')->nullable();
            $table->string('po_name');
            $table->string('po_no');
            $table->decimal('po_amt', 20, 2);
            $table->longText('details');
            $table->longText('asset_details');
            $table->longText('approval_details');
            $table->longText('other_details');
            $table->longText('approved_status');
            $table->enum('approval_req', ['y', 'n'])->default('n');
            $table->enum('status', ['pending approval', 'open', 'partially approved', 'approved', 'rejected'])->default('pending approval');
            $table->binary('requester_id')->nullable();
            $table->binary('bv_id')->nullable();
            $table->binary('dc_id')->nullable();
            $table->binary('location_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_form_data_po');
    }
};
