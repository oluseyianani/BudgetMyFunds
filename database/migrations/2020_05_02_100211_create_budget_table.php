<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 100);
            $table->bigInteger('category_id')->unsigned()->index();
            $table->bigInteger('sub_category_id')->unsigned()->index()->nullable();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->decimal('dedicated_amount', 12, 2);
            $table->date('budget_for_month')->nullable();
            $table->timestamp('budget_created_on')->nullable();
            $table->timestamp('budget_updated_on')->nullable();
            $table->timestamp('budget_deleted_on')->nullable();
        });


        Schema::table('budgets', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('sub_category_id')->references('id')->on('sub_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budgets');
    }
}
