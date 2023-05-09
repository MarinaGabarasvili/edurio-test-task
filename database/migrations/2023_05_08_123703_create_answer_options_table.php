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
        Schema::create('answer_options', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('question_id')->unsigned();
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->integer('scalar_value')->index();
            $table->string('option_text');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answer_options');
    }
};
