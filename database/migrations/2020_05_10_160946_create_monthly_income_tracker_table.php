<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyIncomeTrackerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_income_tracker', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('beneficiary')->unsigned()->index();
            $table->string('income_source')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('date_received');
            $table->bigInteger('creator')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('monthly_income_tracker', function (Blueprint $table) {
            $table->foreign('beneficiary')->references('id')->on('users');
            $table->foreign('creator')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_income_tracker');
    }
}
