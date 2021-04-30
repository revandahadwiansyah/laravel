<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('phonecode');
            $table->string('code');
            $table->string('name');
            $table->timestamps();
        });
        
        // Insert Countries//
        DB::table('countries')->insert(
            array(
                'phonecode' => 60,
                'code' => 'MY',
                'name' => 'MALAYSIA'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
