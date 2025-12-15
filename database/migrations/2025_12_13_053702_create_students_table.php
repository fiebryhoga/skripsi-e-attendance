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
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->string('nis')->unique();
        $table->string('name');
        $table->string('gender')->nullable();
        $table->string('religion')->nullable();
        $table->string('nisn')->nullable();
        $table->string('angkatan', 4);
        $table->string('phone_parent')->nullable();
        $table->string('photo')->nullable();
        
        $table->unsignedBigInteger('classroom_id')->nullable();    
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
