<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_invites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->index();
            $table->unsignedBigInteger('quiz_id');
            $table->boolean('accepted')->nullable();    // 1 if accepted, 0 if declined, null if no action taken
            $table->timestamp('created_at');

            $table->foreign('quiz_id')->references('id')->on('quizzes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_invites');
    }
}
