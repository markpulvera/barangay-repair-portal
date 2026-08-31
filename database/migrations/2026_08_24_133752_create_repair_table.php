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
    Schema::create('repair', function (Blueprint $table) {
        $table->increments('id');
        $table->string('Reporter_name');
        $table->string('Location_landmark');
        $table->string('Contact_number');
        $table->enum('Facility_type', ['Streetlight / Post', 'Drainage / Canal', 'Water Pipe', 'Public Pathway / Road']);
        $table->text('Damage_description');
        $table->date('Date_observed');
        $table->string('Image')->nullable(); 
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_repair');
    }
};
