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
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained(); 
            
            
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete(); 
            
            $table->date('date'); 
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpha', 'Terlambat'])->default('Hadir');
            $table->string('note')->nullable();
            $table->timestamps();

            
            
            $table->unique(['student_id', 'date', 'schedule_id'], 'unique_attendance'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};
