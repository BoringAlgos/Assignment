<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyDetails extends Model
{
    use HasFactory;

    protected $fillable = ['policy_number', 'email'];

    public function vehicle()
    {
        return $this->hasOne(VehicleDetails::class, 'policy_id', 'id');
    }

    // Additional model logic can be added here
}
