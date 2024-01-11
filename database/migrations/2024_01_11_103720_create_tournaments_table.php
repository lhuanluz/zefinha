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
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('rounds');
            $table->dateTime('start_date');
            $table->enum('status', ['aberto', 'em_andamento', 'concluido'])->default('aberto');
            $table->integer('max_decks_per_user')->default(1);
            $table->unsignedBigInteger('user_id'); // Chave estrangeira para o usuário criador/administrador do torneio
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('deck_tournament_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deck_id');
            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('deck_id')->references('id')->on('decks');
            $table->foreign('tournament_id')->references('id')->on('tournaments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

};
