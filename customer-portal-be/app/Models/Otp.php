<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_number',
        'otp',
    ];

    // Define the relationship with PolicyDetails
    public function policyDetails()
    {
        return $this->belongsTo(PolicyDetails::class, 'policy_number', 'policy_number');
    }
}
