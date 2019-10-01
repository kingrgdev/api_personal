<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DateTimeApi extends Migration
{
    /**
     * Run the migrations.s
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_time_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_id', 20)->nullable();
            $table->string('user_id', 10)->nullable();
            $table->string('ACNo', 20)->nullable();
            $table->string('name', 191)->nullable();
            $table->string('email', 191)->nullable(); 
            $table->string('apiKey', 191)->nullable(); 
            $table->timestamp('datetime')->nullable(); 
            $table->string('address', 255)->nullable(); 
            $table->string('longitude', 191)->nullable(); 
            $table->string('latitude', 191)->nullable(); 
            $table->string('report', 255)->nullable(); 
            $table->string('state', 30)->nullable();
            $table->string('deviceID', 30)->nullable();
            $table->string('status', 30)->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('date_time_records');
    }
}
