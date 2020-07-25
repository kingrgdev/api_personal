<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_id', 20);
            $table->string('apiKey', 191)->nullable();
            $table->string('ACNo', 20)->nullable();
            $table->string('name', 191);
            $table->string('email', 191)->unique;
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->tinyInteger('deleted')->default('0');
            $table->string('updated_by', 191)->nullable();
            $table->string('created_by', 191)->nullable();
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
        Schema::dropIfExists('users');
    }
}
