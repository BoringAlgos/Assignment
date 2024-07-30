<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\ClaimDocument;
use Illuminate\Support\Facades\Storage;
use App\Models\Incident;
use Illuminate\Support\Facades\DB;
use App\Jobs\TransitionClaimState;
use App\Models\States\AssignedToSurveyor;
use App\Models\States\AssignedToAdjustor;
use App\Models\ClaimRevision;
use App\Models\States\ApproveClaim;
use App\Models\States\ApproveClaimUpdates;
use App\Models\States\RejectClaim;

class ClaimsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:client');
    // }
    public function store(Request $request)
    {
        \Log::info('Received request:', $request->all());
        // Validate the incoming request data
        $request->validate([
            'customer_id' => 'required',
            'policy_id' => 'required',
            'vehicle_id' => 'required',
            'location_area_code' => 'required|string',
            'incident_details' => 'required|string',
            'document.*' => 'required|mimes:pdf,docx', // Adjust allowed file types
            'document_type.*' => 'required|string|in:insurance,photo,police_report', // Adjust allowed document types
        ]);

        // Create a new claim
        DB::beginTransaction();

        try {
            $claim = Claim::create([
                'customer_id' => $request->customer_id,
                'policy_id' => $request->policy_id,
                'assigned_to' => null,
                'claim_status' => 'Claim Submitted',
            ]);

            $incident = Incident::create([
                'claim_id' => $claim->id,
                'vehicle_id' => $request->vehicle_id,
                'location_area_code' => $request->location_area_code,
                'incident_description' => $request->incident_details,
            ]);

            // Upload and store documents
            $documents = $request->file('document');
            $documentTypes = $request->input('document_type');

            $path = $documents->store('public');
            $publicUrl = url(Storage::url($path));
            // Generate public URL
            ClaimDocument::create([
                'claim_id' => $claim->id,
                'document_type' => $documentTypes,
                'link' => $publicUrl,
            ]);
            // If everything went well, commit the transaction
            DB::commit();
            // Set the initial state
            // $claim->update(['state' => 'claim_submitted']);
            TransitionClaimState::dispatch($claim, AssignedToSurveyor::class)->onQueue('default');
            return response()->json(['message' => 'Claim created successfully'], 201);
            // Optionally, you can return a success response here
        } catch (\Exception $e) {
            // If an exception occurs, rollback the transaction
            DB::rollback();

            // Optionally, you can log the exception or return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function review(Request $request)
    {
        \Log::info('Review Request request:', $request->all());
        // Validate the incoming request data
        $request->validate([
            'claimId' => 'required|exists:claims,id',
            'items' => 'required',
        ]);

        $claimId = $request->claimId;
        $items = $request->items;
        $decodedItems = json_decode($items, true);

        if (is_null($decodedItems)) {
            return response()->json(['error' => 'Invalid items format'], 400);
        }
        DB::beginTransaction();

        try {
            // Retrieve the claim
            $claim = Claim::findOrFail($claimId);

            // Store the existing claim data in claim_revisions table
            ClaimRevision::create([
                'claim_id' => $claim->id,
                'assigned_to' => $claim->assigned_to,
                'claim_status' => $claim->claim_status,
                'job_status' => $claim->job_status,
                'claim_work' => $claim->claim_work,
            ]);

            // Convert items array to JSON and update the claim_work
            $claim->update([
                'claim_work' => json_encode($decodedItems),
            ]);

            // Transition to AssignedToAdjustor
            TransitionClaimState::dispatch($claim, AssignedToAdjustor::class)->onQueue('default');

            DB::commit();

            return response(['message' => 'Claim reviewed and updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['error' => $e->getMessage()], 500);
        }
    }

    public function approve(Request $request)
    {
        \Log::info('Review Request request:', $request->all());
        // Validate the incoming request data
        $request->validate([
            'claimId' => 'required|exists:claims,id',
            'items' => 'required',
        ]);

        $claimId = $request->claimId;
        $items = $request->items;
        $isUpdated = $request->formUpdated;
        $decodedItems = json_decode($items, true);

        if (is_null($decodedItems)) {
            return response()->json(['error' => 'Invalid items format'], 400);
        }
        DB::beginTransaction();

        try {
            // Retrieve the claim
            $claim = Claim::findOrFail($claimId);

            // Store the existing claim data in claim_revisions table
            ClaimRevision::create([
                'claim_id' => $claim->id,
                'assigned_to' => $claim->assigned_to,
                'claim_status' => $claim->claim_status,
                'job_status' => $claim->job_status,
                'claim_work' => $claim->claim_work,
            ]);

            // Convert items array to JSON and update the claim_work
            $claim->update([
                'claim_work' => json_encode($decodedItems),
                'assigned_to' => null,
            ]);

            if($isUpdated)
            {
                TransitionClaimState::dispatch($claim, ApproveClaimUpdates::class)->onQueue('default');
            }else{
                TransitionClaimState::dispatch($claim, ApproveClaim::class)->onQueue('default');
            }
            

            DB::commit();

            return response(['message' => 'Claim reviewed and updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response(['error' => $e->getMessage()], 500);
        }
    }


    public function list(Request $request)
    {
        $role = $request->input('role');
        $user = $request->input('user');

        try {
            if ($role == 'Admin') {
                // Fetch all claims with related data, sorted by assigned_to (null first) and then created_at
                $claims = Claim::with(['incident', 'claimDocuments'])
                    ->orderByRaw('assigned_to IS NULL DESC, created_at DESC')
                    ->get();
            } else {
                // Fetch claims for a specific user with related data, sorted by created_at
                $claims = Claim::with(['incident', 'claimDocuments'])
                    ->where('assigned_to', $user)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }

            // You can customize the response format as needed
            return response()->json(['claims' => $claims]);
        } catch (\Exception $e) {
            // Handle exceptions if any
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    
    public function fetchClaimWork(Request $request)
    {
        $request->validate([
            'claimId' => 'required|exists:claims,id', // Ensure the claimId is provided and exists
        ]);

        $claimId = $request->claimId;

        try {
            // Assuming claim_work is a column in your claims table that stores JSON data
            $claim = Claim::findOrFail($claimId);
            $claimWork = json_decode($claim->claim_work, true);

            if (!$claimWork) {
                return response()->json(['error' => 'No claim work data found'], 404);
            }

            return response()->json(['claimWork' => $claimWork], 200);
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




}
