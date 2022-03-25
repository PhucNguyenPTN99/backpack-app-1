<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAcademyProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academy_programs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',255);
            $table->string('slug',255);
            $table->timestamps();
            $table->timestamp('deleted_at');
            $table->string('status',255);
            $table->string('subtitle',255);
            $table->text('description');
            $table->text('logo_image');
            $table->text('banner_image');
            $table->json('meta');
            $table->json('articles');
            $table->text('about_infos');
            $table->text('about_banner');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('academy_programs');
    }
}
