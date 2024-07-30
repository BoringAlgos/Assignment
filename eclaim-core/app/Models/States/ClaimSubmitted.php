<?php

namespace App\Models\States;

// AssignedToSurveyor.php
use App\Models\States\ClaimState;

class ClaimSubmitted extends ClaimState
{
    public $nextStatus = 'Assigned To Surveyor';
    
}
