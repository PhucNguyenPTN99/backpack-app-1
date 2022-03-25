<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAcademyModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academy_modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('academy_program_id');
            $table->string('name',255);
            $table->string('slug',255);
            $table->timestamps();
            $table->timestamp('deleted_at');
            $table->text('description');
            $table->integer('order')->default(0);
            $table->text('banner_image');
            $table->integer('parent_id')->default(0);
            $table->text('sub_modules_intro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academy_modules');
    }
}
