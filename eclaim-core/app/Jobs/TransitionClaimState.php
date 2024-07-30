<?php

namespace App\Jobs;

// TransitionClaimState.php

use App\Models\Claim;
use App\Models\Incident;
use App\Models\States\ClaimState;
use Spatie\ModelStates\Exceptions\TransitionNotFound;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\States\ApproveClaim;
use App\Models\States\ApproveClaimUpdates;
use App\Models\States\RejectClaim;

class TransitionClaimState implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $claim;
    public $targetState;

    public function __construct(Claim $claim, string $targetState)
    {
        $this->claim = $claim;
        $this->targetState = $targetState;
    }

    public function handle()
    {
        try {
            \Log::info('claim state transition started:', ['claim' => $this->claim->id]);
            // Create an instance of the target state
            // Get the current state of the claim

            $this->followUpTask();
            // Perform the transition to the target state


            \Log::info('Claim state transition completed:', ['claim' => $this->claim->id, 'targetState' => $this->targetState]);
        } catch (TransitionNotFound $e) {
            // Handle the case where the transition is not defined
            \Log::error('Error triggering claim state transition:', ['error' => $e->getMessage()]);
        }
    }

    public function followUpTask()
    {
        $currentState = $this->claim->claimState;
        $gatewayAdmin = env('GATEWAY_ADMIN');
        $response = Http::post("http://$gatewayAdmin/api/generate-token-client", [
            'token_name' => 'customer_portal',
        ]);

        $tokenResponse = $response->json();


        $token = $tokenResponse['token'];
        $claimStatus = $this->claim->claim_status;
        if (!in_array($this->targetState, [ApproveClaim::class, ApproveClaimUpdates::class, RejectClaim::class])) {
            // Fetch area code from the related Incident model
            $areaCode = $this->claim->incident->location_area_code;
            \Log::info('Post Data:', ['area_code' => $areaCode, 'claim_status' => $claimStatus]);

            $response = Http::withToken($token)
                ->post("http://$gatewayAdmin/api/getUser", [
                    'status' => $claimStatus, // Replace with your actual status
                    'area_code' => $areaCode, // Replace with your actual area code
                ]);

            // Access the response data
            $data = $response->json();
            \Log::info('Post Response:', $data);
            // Check if the request was successful
            if ($response->successful()) {
                $userId = $data['user_id'];
                // Do something with the $userId

                $this->claim->update(['assigned_to' => $userId, 'claim_status' => $currentState->nextStatus]);
                $currentState->transitionTo($this->targetState);
            } else {
                $errorMessage = $data['message'];
                // Handle the error
                echo "Error: $errorMessage";
            }
        }else{
            $currentState->transitionTo($this->targetState);
            $currentState = $this->claim->claimState;
            $this->claim->update(['claim_status' => $currentState->nextStatus]);
        }


    }
}
