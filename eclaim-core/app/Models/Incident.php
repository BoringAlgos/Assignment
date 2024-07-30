<?php

// app/Models/Incident.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\ModelStates\HasStates;
use App\Models\States\ClaimState;

class Incident extends Model
{
    use HasStates;

    protected $stateClass = ClaimState::class;

    protected $fillable = [
        'claim_id',
        'vehicle_id',
        'location_area_code',
        'incident_description',
    ];
}
