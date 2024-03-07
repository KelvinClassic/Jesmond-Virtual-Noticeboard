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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('poster_type_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('location');
            $table->text('description')->nullable();
            $table->date('event_start_date');
            $table->date('event_end_date');
            $table->time('event_start_time');
            $table->time('event_end_time');
            $table->string('active')->default(false);
            $table->timestamp('approved_date')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('poster_type_id')->references('id')->on('poster_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('events');
    }
};
