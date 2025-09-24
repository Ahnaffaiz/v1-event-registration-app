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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('host_id')->constrained('hosts')->onDelete('cascade');
            $table->string('name');
            $table->string('image')->nullable();
            $table->longText('desc')->nullable();
            $table->dateTime('registration_start_date');
            $table->dateTime('registration_end_date');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->float('ticket_price')->nullable();
            $table->boolean('require_approval')->default(false);
            $table->boolean('is_public')->default(true);
            $table->string('short_link')->nullable();
            $table->integer('capacity')->nullable();
            $table->timestamps();
            $table->enum('status', ['active', 'inactive', 'cancelled'])->default('active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
