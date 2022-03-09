<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clienti', function (Blueprint $table) {
            $table->bigInteger('CIF')->primary();
            $table->string('Denumire', 100)->nullable()->unique('Denumire');
            $table->string('ContBancar', 24)->nullable();
            $table->string('Banca', 50)->nullable();
            $table->string('Sediu')->nullable();
            $table->string('Judet', 20)->nullable();
            $table->string('NrRegCom', 20)->nullable();
            $table->string('RO', 2)->nullable();
            $table->string('NrContract', 10)->nullable();
            $table->date('DataContract')->nullable();
            $table->decimal('Valoare', 10, 0)->nullable();
            $table->integer('NrAutorizatie')->nullable();
            $table->date('DataAutorizatie')->nullable();
            $table->date('DataExpirareAutorizatie')->nullable();
            $table->string('Furnizor', 10)->nullable();
            $table->string('Note', 250);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clienti');
    }
}
