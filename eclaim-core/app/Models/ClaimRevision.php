<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimRevision extends Model
{
    // Fillable attributes for mass assignment
    protected $fillable = [
        'claim_id', 'assigned_to', 'claim_status', 'job_status', 'claim_work'
    ];

    // Relationship with the Claim model
    public function claim()
    {
        return $this->belongsTo(Claim::class, 'claim_id');
    }

    // Additional methods and attributes can be added here as needed
}
