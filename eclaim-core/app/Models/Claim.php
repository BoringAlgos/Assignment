<?php

// app/Models/Claim.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\Models\States\ClaimState;

class Claim extends Model
{
    use HasStates;

    protected $casts = [
        'claimState' => ClaimState::class,
    ];

    protected $fillable = [
        'customer_id',
        'policy_id',
        'claim_status',
        'claim_work',
        'job_status',
        'assigned_to'
    ];

    public function incident()
    {
        return $this->hasOne(Incident::class);
    }


    public function claimDocuments()
    {
        return $this->hasMany(ClaimDocument::class);
    }
}
