<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetExpenseTrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_expense_trackings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('budget_id')->unsigned()->index();
            $table->decimal('amount_spent', 12, 2);
            $table->bigInteger('spender')->unsigned()->index();
            $table->string('reason_for_spend')->nullable();
            $table->timestamp('expenses_created_on')->nullable();
            $table->timestamp('expenses_updated_on')->nullable();
        });

        Schema::table('budget_expense_trackings', function (Blueprint $table) {
            $table->foreign('budget_id')->references('id')->on('budgets');
            $table->foreign('spender')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_expense_trackings');
    }
}
