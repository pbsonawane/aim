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
        Schema::create('en_pr_po_attachment', function (Blueprint $table) {
            $table->binary('attach_id'); // Primary Key (can also use $table->primary('attach_id') if it's the primary key)
            $table->binary('pr_po_id')->nullable();
            $table->string('file_title', 255);
            $table->binary('pr_vendor_id')->nullable();
            $table->enum('type', ['invoice', 'document']);
            $table->enum('attachment_type', ['pr', 'po', 'qu'])->default('pr');
            $table->text('attachment_name');
            $table->binary('created_by')->nullable();
            $table->enum('status', ['y', 'n', 'd'])->default('y');
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
        Schema::dropIfExists('en_pr_po_attachment');
    }
};
