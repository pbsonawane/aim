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
        Schema::create('en_failed_jobs', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID field
            $table->text('connection'); // Job connection information
            $table->text('queue'); // Queue name
            $table->longText('payload'); // Serialized job data
            $table->longText('exception'); // Exception information in case of failure
            $table->timestamp('failed_at')->useCurrent(); // Timestamp when the job failed (default is CURRENT_TIMESTAMP)
            $table->timestamps(); // Created at and updated at timestamps (optional)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_failed_jobs');
    }
};
