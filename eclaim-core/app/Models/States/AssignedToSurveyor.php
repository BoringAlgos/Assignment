<?php

namespace App\Models\States;

// AssignedToSurveyor.php

use App\Models\States\ClaimState;

class AssignedToSurveyor extends ClaimState
{
    public $nextStatus = 'Assigned To Adjustor';
    
    public function handle()
    {
      //  \Log::info('Claim state transition completed:', ['claim' => 'here']);
    }
}
