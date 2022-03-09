<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFurnizoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('furnizori', function (Blueprint $table) {
            $table->collation = 'utf8mb4_general_ci';

            $table->string('id', 10)->primary();
            $table->bigInteger('CUI');
            $table->string('Denumire', 100);
            $table->string('ContBancar', 24);
            $table->string('Banca', 50);
            $table->string('Sediu');
            $table->string('Judet', 20);
            $table->string('NrRegCom', 20);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('furnizors');
    }
}
