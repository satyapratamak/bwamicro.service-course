<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreignId('t_courses_id')->constrained('t_courses')->onDelete('cascade');
            $table->integer('ratings')->default(1);
            $table->longText('note')->nullable();
            $table->unique('user_id', 't_courses_id');
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
        Schema::dropIfExists('t_reviews');
    }
};
