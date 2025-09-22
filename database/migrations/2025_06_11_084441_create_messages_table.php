<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');

            $table->text('message');

            // added column in later migration
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            // helpful indexes (explicit names to match original)
            $table->index(['receiver_id', 'read_at'], 'messages_receiver_read_idx');
            $table->index(['sender_id', 'receiver_id'], 'messages_sender_receiver_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
