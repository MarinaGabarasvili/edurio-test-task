<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('question_id')->unsigned();
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->bigInteger('answer_option_id')->unsigned()->nullable();
            $table->foreign('answer_option_id')
                ->references('id')
                ->on('answer_options')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->text('text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
