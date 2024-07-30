<?php

namespace App\Models\States;

// AssignedToSurveyor.php

use App\Models\States\ClaimState;

class RejectClaim extends ClaimState
{
    public $nextStatus = 'Claim Rejected';
    
    public function handle()
    {
      //  \Log::info('Claim state transition completed:', ['claim' => 'here']);
    }
}
