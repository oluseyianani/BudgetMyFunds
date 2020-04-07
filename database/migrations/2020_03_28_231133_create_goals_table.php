<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description');
            $table->decimal('amount', 12, 2);
            $table->decimal('monthly_contributions', 12, 2);
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('goal_categories_id')->unsigned()->index();
            $table->dateTime('due_date');
            $table->boolean('auto_compute');
            $table->boolean('completed')->default(0);
            $table->timestamp('goal_created_on')->nullable();
            $table->timestamp('goal_updated_on')->nullable();
            $table->timestamp('goal_deleted_on')->nullable();
        });

        Schema::table('goals', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('goal_categories_id')->references('id')->on('goal_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goals');
    }
}
