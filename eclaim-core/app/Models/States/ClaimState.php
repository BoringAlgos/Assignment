<?php


namespace App\Models\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;
use App\Models\States\AssignedToSurveyor;
use App\Models\States\AssignedToAdjustor;
use App\Models\States\ClaimSubmitted;
use App\Models\States\ApproveClaim;
use App\Models\States\RejectClaim;
use App\Models\States\ApproveClaimUpdates;
class ClaimState extends State
{
    public static function config(): StateConfig
    {
        return parent::config()
            ->default(ClaimSubmitted::class)
            ->allowTransition(ClaimSubmitted::class, AssignedToSurveyor::class)
            ->allowTransition(AssignedToSurveyor::class, AssignedToAdjustor::class)
            ->allowTransition(AssignedToAdjustor::class, RejectClaim::class)
            ->allowTransition(AssignedToAdjustor::class, ApproveClaimUpdates::class)
            ->allowTransition(AssignedToAdjustor::class, ApproveClaim::class);
    }
}
