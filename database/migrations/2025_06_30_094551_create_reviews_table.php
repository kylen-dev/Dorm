<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');

            // final state: reviews reference properties (not rooms)
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');

            $table->text('comment');
            $table->text('reply')->nullable();

            // tiny unsigned integer (1-5)
            $table->unsignedTinyInteger('rating');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
