<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    // Explicitly set singular table name matching your migration
    protected $table = 'repair';

    protected $fillable = [
        'user_id',
        'Reporter_name',
        'Location_landmark',  
        'latitude',
        'longitude',  
        'Risk_level',
        'Facility_type',
        'Damage_description',
        'Date_observed',
        'Image',
        'Status',
        'Assigned_department',
        'proof_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}