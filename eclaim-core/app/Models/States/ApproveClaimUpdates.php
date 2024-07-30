<?php

namespace App\Models\States;

// AssignedToSurveyor.php

use App\Models\States\ClaimState;

class ApproveClaimUpdates extends ClaimState
{
    public $nextStatus = 'Claim Approved with Updates';
    
    public function handle()
    {
      //  \Log::info('Claim state transition completed:', ['claim' => 'here']);
    }
}
