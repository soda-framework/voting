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
        Schema::create('voting_nominees', function (Blueprint $table) {
            $table->increments('id');

            foreach (config('soda.votes.voting.fields.nominee') as $field_name => $field) {
                if (in_array(@$field['type'], ['text','fancyupload','textarea'])) {
                    $table->string($field_name, 255)->nullable();
                } elseif (in_array(@$field['type'], ['tinymce'])) {
                    $table->text($field_name)->nullable();
                } elseif (in_array(@$field['type'], ['toggle'])) {
                    $table->boolean($field_name)->nullable();
                }
            }

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
