<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("comment");
            $table->dateTime("deadline");
            $table->integer("progress");
            $table->enum("state",["compeleted","in progress","awaiting"]);
            $table->bigInteger("user_id")->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on("users")->onDelete('cascade')->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
