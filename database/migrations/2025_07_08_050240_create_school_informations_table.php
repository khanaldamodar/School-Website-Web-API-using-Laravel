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
        Schema::create('school_informations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of the school');
            $table->string('address')->comment('Address of the school');
            $table->string('phone')->comment('Contact phone number of the school');
            $table->string('email')->comment('Contact email of the school');
            $table->text('description')->nullable()->comment('Description of the school');
            $table->string('logo')->nullable()->comment('Logo of the school');
            $table->time('school_start_time')->nullable()->comment('Start time of the school day');
            $table->time('school_end_time')->nullable()->comment('End time of the school day');
            $table->unsignedBigInteger('created_by')->nullable()->comment('ID of the user who created this record');
            $table->unsignedBigInteger('updated_by')->nullable()->comment('ID of the user who last updated this record');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null')->comment('Foreign key to users table for created_by');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null')->comment('Foreign key to users table for updated_by');

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
        Schema::dropIfExists('school_informations');
    }
};
