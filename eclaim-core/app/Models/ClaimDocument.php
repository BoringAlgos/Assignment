<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'document_type',
        'link',
    ];

    // Define the relationship with the Claim model
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
