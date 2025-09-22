<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('landlord_id')->constrained('users')->onDelete('cascade');

            // optional specific room in the property
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');

            // contract / terms
            $table->text('terms')->nullable();
            $table->text('landlord_terms')->nullable();

            // booking status (was enum historically; keep as string to avoid PG enum headaches)
            $table->string('status', 20)->default('pending');

            $table->boolean('signed_by_tenant')->default(false);
            $table->boolean('signed_by_landlord')->default(false);
            $table->timestamp('finalized_at')->nullable();

            // booking schedule
            $table->date('booking_date')->nullable();
            $table->time('booking_time')->nullable();

            // tenant info snapshot (added by later migration)
            $table->string('tenant_name')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();

            // contract management fields (was enum originally)
            $table->string('contract_status', 20)->default('active');
            $table->text('termination_reason')->nullable();
            $table->timestamp('terminated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
