<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curso_estudiante', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger("curso_id");
            $table->foreign("curso_id")->references("id")->on("cursos")->onDelete("cascade");

            $table->unsignedBigInteger("estudiante_id");
            $table->foreign("estudiante_id")->references("id")->on("estudiantes")->onDelete("cascade");
            
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
        Schema::dropIfExists('curso_estudiante');
    }
};
