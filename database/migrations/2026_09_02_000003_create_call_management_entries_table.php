<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('call_management_entries', function (Blueprint $table) {
            $table->id();
            $table->string('firm_name', 200);
            $table->string('contact_person_name', 200);
            $table->string('mobile_number', 15);
            $table->string('customer_type', 100)->nullable();
            $table->text('address')->nullable();
            $table->unsignedBigInteger('pincode_id');
            $table->string('pincode', 20);
            $table->string('city', 150)->nullable();
            $table->string('district', 150)->nullable();
            $table->string('state', 150)->nullable();
            $table->unsignedBigInteger('assigned_user_id');
            $table->string('custom_column_1')->nullable();
            $table->string('custom_column_2')->nullable();
            $table->string('custom_column_3')->nullable();
            $table->string('custom_column_4')->nullable();
            $table->string('status', 30)->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['mobile_number', 'status']);
            $table->foreign('pincode_id')->references('id')->on('pincodes');
            $table->foreign('assigned_user_id')->references('id')->on('users');
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_management_entries');
    }
};
