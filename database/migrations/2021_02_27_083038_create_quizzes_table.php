<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->integer('lesson_id')->unsigned();
            $table->string('pelajaran');
            $table->string('question_text');
            $table->string('answer_options');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return voidx
     */
    public function down()
    {
        Schema::dropIfExists('quizzes');
    }
}
