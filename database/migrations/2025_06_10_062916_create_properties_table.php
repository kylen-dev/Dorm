<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('title');
            $table->text('description');
            $table->string('location')->nullable();
            $table->integer('price');
            $table->integer('room_count')->default(1);
            $table->string('image')->nullable();

            // coordinates (added by later migration)
            $table->decimal('latitude', 18, 15)->nullable();
            $table->decimal('longitude', 18, 15)->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
