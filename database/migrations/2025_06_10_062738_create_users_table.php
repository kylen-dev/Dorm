<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // original student/school id
            $table->string('user_id')->unique();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();

            $table->string('gender')->nullable();
            $table->date('dob')->nullable();

            $table->string('role'); // 'admin', 'landlord', 'tenant' (validate in app)
            $table->boolean('is_approved')->default(false);

            // Profile image fields (kept both to avoid breaking code that references either one)
            $table->string('profile_image')->nullable();
            $table->string('update_profile_image')->nullable();

            // contact / presence
            $table->string('contact_number')->nullable();
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen')->nullable();

            // coordinates + location
            $table->decimal('latitude', 18, 15)->nullable();
            $table->decimal('longitude', 18, 15)->nullable();
            $table->string('location')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
