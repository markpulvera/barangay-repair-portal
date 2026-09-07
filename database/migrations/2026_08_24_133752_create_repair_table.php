<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Reporter_name');
            $table->string('Location_landmark');
            $table->enum('Risk_level', ['Low', 'Medium', 'High', 'Critical'])->default('Medium');
            $table->enum('Facility_type', ['Streetlight / Post', 'Drainage / Canal', 'Water Pipe', 'Public Pathway / Road']);
            $table->text('Damage_description');
            $table->date('Date_observed');
            $table->string('Image')->nullable();
            
            // ADMIN MANAGEMENT COLUMNS
            $table->enum('Status', ['Pending', 'In Progress', 'Resolved'])->default('Pending');
            $table->string('Assigned_department')->nullable()->default('Unassigned');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair');
    }
};