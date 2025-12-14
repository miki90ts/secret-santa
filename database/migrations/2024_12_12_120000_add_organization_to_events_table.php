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
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('organization_id')->after('id')->nullable()->constrained()->onDelete('cascade');
            $table->dropUnique(['year']);
            $table->unique(['organization_id', 'year']);
            $table->string('slug')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropUnique(['organization_id', 'year']);
            $table->unique('year');
            $table->dropForeign(['organization_id']);
        });
    }
};
