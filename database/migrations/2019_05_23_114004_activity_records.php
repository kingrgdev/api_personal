<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActivityRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token_id', 255)->nullable();
            $table->string('by_user_id', 10)->nullable();
            $table->string('for_company_id', 191)->nullable();
            $table->string('type', 100)->nullable();
            $table->string('status', 100)->nullable();
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
        //
        Schema::dropIfExists('activity_records');
    }
}
