<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnAssetDetailsBkbTable extends Migration
{
    public function up()
    {
        Schema::create('en_asset_details_bkb', function (Blueprint $table) {
            $table->binary('asset_detail_id', 16)->primary();
            $table->binary('asset_id', 16)->nullable();
            $table->binary('vendor_id', 16)->nullable();
            $table->string('purchasecost', 100)->collation('utf8mb4_unicode_ci');
            $table->longText('asset_details')->collation('utf8mb4_bin');
            $table->enum('auto_discovered', ['y', 'n'])->default('n')->collation('utf8mb4_unicode_ci');
            $table->text('add_comment')->collation('utf8mb4_unicode_ci');
            $table->timestamp('acquisitiondate')->useCurrent()->onUpdate('CURRENT_TIMESTAMP');
            $table->timestamp('expirydate')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('warrantyexpirydate')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('en_asset_details_bkb');
    }
}
