<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('decks', function (Blueprint $table) {
            $table->string('urlLigamagic')->nullable();
            $table->decimal('valorMin', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('decks', function (Blueprint $table) {
            $table->dropColumn('urlLigamagic');
            $table->dropColumn('valorMin');
        });
    }
};
