<?php

namespace App\Models\States;

// AssignedToSurveyor.php

use App\Models\States\ClaimState;

class ApproveClaim extends ClaimState
{
    public $nextStatus = 'Claim Approved';
    
    public function handle()
    {
      //  \Log::info('Claim state transition completed:', ['claim' => 'here']);
    }
}
