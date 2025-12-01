<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->year('year')->unique();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->date('registration_start')->nullable();
            $table->date('registration_end')->nullable();
            $table->date('assignment_date')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('assignments_made')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
