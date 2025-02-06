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
        Schema::create('en_contract_details', function (Blueprint $table) {
            $table->binary('contract_details_id', 16)->primary();
            $table->binary('contract_id', 16)->nullable();
            $table->text('support');
            $table->text('description');
            $table->string('attachments', 100);
            $table->string('cost', 100);
            $table->longText('asset_id')->collation('utf8mb4_bin');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            // Add necessary indexes here
            $table->index('contract_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_contract_details');
    }
};
