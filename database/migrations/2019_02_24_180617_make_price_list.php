<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakePriceList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->date('date');
            $table->unsignedInteger('price');
            $table->string('color');
            $table->string('shape');
            $table->string('clarity');
            $table->decimal('low_size',8,2);
            $table->decimal('high_size',8,2);
            $table->timestamps();
            $table->primary(['shape', 'low_size', 'high_size','color','clarity']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
}
