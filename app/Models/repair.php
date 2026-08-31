<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $table = 'repair';
    protected $fillable = [
        'Reporter_name',
        'Location_landmark',
        'Contact_number',
        'Facility_type',
        'Damage_description',
        'Date_observed',
        'image',
    ];
}
