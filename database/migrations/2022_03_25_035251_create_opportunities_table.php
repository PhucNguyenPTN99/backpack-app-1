<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type',255);
            $table->string('title',255);
            $table->string('slug',255);
            $table->text('description');
            $table->text('banner_image');
            $table->timestamp('publish_date');
            $table->timestamp('end_date');
            $table->string('status',255);
            $table->timestamps();
            $table->timestamp('deleted_at');
            $table->text('mobile_banner_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opportunities');
    }
}
