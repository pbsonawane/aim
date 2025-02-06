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
        Schema::create('en_ci_vendors', function (Blueprint $table) {
            $table->uuid('vendor_id')->primary(); // Primary key as binary(16) UUID
            $table->string('vendor_unique_id', 50)->nullable(); // vendor_unique_id column
            $table->string('vendor_name', 255); // vendor_name column
            $table->string('vendor_email', 255)->nullable(); // vendor_email column
            $table->string('vendor_ref_id', 50)->nullable(); // vendor_ref_id column
            $table->string('contact_person', 50); // contact_person column
            $table->string('contactno', 50); // contactno column
            $table->string('address', 255); // address column
            $table->string('city', 255)->nullable(); // city column
            $table->string('pincode', 50)->nullable(); // pincode column
            $table->string('warehouse_location', 500)->nullable(); // warehouse_location column
            $table->string('vendor_gst_no', 255)->nullable(); // vendor_gst_no column
            $table->string('vendor_pan', 255)->nullable(); // vendor_pan column
            $table->string('bank_name', 255)->nullable(); // bank_name column
            $table->string('vendor_gst_no_file', 255)->nullable(); // vendor_gst_no_file column
            $table->string('vendor_pan_file', 255)->nullable(); // vendor_pan_file column
            $table->string('is_msme_reg', 50)->nullable(); // is_msme_reg column
            $table->text('meme_reg_num')->nullable(); // meme_reg_num column
            $table->text('msme_certificate')->nullable(); // msme_certificate column
            $table->text('products_services_offered')->nullable(); // products_services_offered column
            $table->text('associate_oem')->nullable(); // associate_oem column
            $table->string('delivery_time', 100)->nullable(); // delivery_time column
            $table->text('payment_terms')->nullable(); // payment_terms column
            $table->string('annual_turnover', 100)->nullable(); // annual_turnover column
            $table->text('known_client')->nullable(); // known_client column
            $table->string('bank_name_file', 255)->nullable(); // bank_name_file column
            $table->text('bank_address')->nullable(); // bank_address column
            $table->string('bank_branch', 255)->nullable(); // bank_branch column
            $table->bigInteger('bank_account_no')->nullable(); // bank_account_no column
            $table->string('ifsc_code', 40)->nullable(); // ifsc_code column
            $table->bigInteger('micr_code')->nullable(); // micr_code column
            $table->string('account_type', 255)->nullable(); // account_type column
            $table->text('director_name')->nullable(); // director_name column
            $table->string('director_contact_no', 100)->nullable(); // director_contact_no column
            $table->string('director_email', 100)->nullable(); // director_email column
            $table->text('sales_officer_name')->nullable(); // sales_officer_name column
            $table->string('sales_officer_contact_no', 100)->nullable(); // sales_officer_contact_no column
            $table->string('sales_officer_email', 100)->nullable(); // sales_officer_email column
            $table->text('account_officer_name')->nullable(); // account_officer_name column
            $table->string('account_officer_contact_no', 100)->nullable(); // account_officer_contact_no column
            $table->string('account_officer_email', 100)->nullable(); // account_officer_email column
            $table->string('any_legal_notices', 50)->nullable(); // any_legal_notices column
            $table->text('legal_notice_elaborate')->nullable(); // legal_notice_elaborate column
            $table->string('is_legal_requirements', 100)->nullable(); // is_legal_requirements column
            $table->string('worker_minimum_age', 100)->nullable(); // worker_minimum_age column
            $table->string('submit_original_documents', 100)->nullable(); // submit_original_documents column
            $table->string('any_serious_incidents', 100)->nullable(); // any_serious_incidents column
            $table->text('elaborate_serious_incidents')->nullable(); // elaborate_serious_incidents column
            $table->string('is_anti_bribe_policy', 100)->nullable(); // is_anti_bribe_policy column
            $table->string('is_health_safety_policy', 100)->nullable(); // is_health_safety_policy column
            $table->string('is_env_regulation', 100)->nullable(); // is_env_regulation column
            $table->text('elaborate_env_regulation')->nullable(); // elaborate_env_regulation column
            $table->text('name')->nullable(); // name column
            $table->string('date', 100)->nullable(); // date column
            $table->string('designation', 100)->nullable(); // designation column
            $table->longText('vendors_assets')->nullable()->collation('utf8mb4_bin'); // vendors_assets column with utf8mb4_bin collation
            $table->enum('status', ['y', 'n', 'd'])->default('y'); // status column with default 'y'
            $table->timestamp('created_at')->nullable(); // created_at timestamp column
            $table->timestamp('updated_at')->nullable(); // updated_at timestamp column
            $table->longText('approve_status')->nullable()->collation('utf8mb4_bin'); // approve_status column with utf8mb4_bin collation
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('en_ci_vendors');
    }
};
