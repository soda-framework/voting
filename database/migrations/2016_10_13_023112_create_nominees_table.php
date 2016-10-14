<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNomineesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
       */
    public function up()
    {
      Schema::create('voting_nominees', function(Blueprint $table){
          $table->increments('id');
          $table->string('name', 128);
          $table->string('description', 255);
          $table->text('details');
          $table->string('image', 255);
          $table->integer('category_id');
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
      Schema::drop('voting_nominees');
    }
}
