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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('survey_id')->unsigned();
            $table->foreign('survey_id')
                ->references('id')
                ->on('surveys')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->enum('type', ['radio', 'text'])->default('text');
            $table->text('text');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
