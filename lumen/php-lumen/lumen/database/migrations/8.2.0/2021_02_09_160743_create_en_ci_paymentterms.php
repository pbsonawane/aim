<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan as Artisan;
class CreateEnCiPaymentterms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       if (!Schema::hasTable('en_ci_paymentterms')){
            Schema::create('en_ci_paymentterms', function (Blueprint $table){
                $table->uuid('paymentterm_id');
                $table->primary('paymentterm_id'); 
                $table->string('payment_term');
                $table->enum("status",array("y","n","d"))->default("y");
                $table->timestamps();
            });
            DB::statement('ALTER TABLE `en_ci_paymentterms` MODIFY `paymentterm_id` BINARY(16);');   
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_ci_paymentterms');
    }
}
