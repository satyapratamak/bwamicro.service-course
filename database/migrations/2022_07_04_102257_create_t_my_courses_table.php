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
        Schema::create('t_my_courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('t_courses_id')->constrained('t_courses')->onDelete('cascade');
            $table->integer('user_id');
            $table->unique(['t_courses_id', 'user_id']);
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
        Schema::dropIfExists('t_my_courses');
    }
};
