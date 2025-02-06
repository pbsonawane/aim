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
        Schema::create('en_bill_to', function (Blueprint $table) {
            $table->binary('billto_id', 16)->primary();
            $table->binary('locations', 16)->nullable();
            $table->string('company_name', 255)->collation('utf8mb4_unicode_ci');
            $table->text('address')->collation('utf8mb4_unicode_ci');
            $table->string('pan_no', 10)->collation('utf8mb4_unicode_ci');
            $table->string('gstn', 20)->collation('utf8mb4_unicode_ci');
            $table->enum('status', ['y', 'n', 'd'])->default('y')->collation('utf8mb4_unicode_ci');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_bill_to');
    }
};
