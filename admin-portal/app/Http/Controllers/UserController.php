<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'area_pincode', 'availability', 'created_at', 'updated_at')
            ->with('roles:name') // Include user roles in the query
            ->get();

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    public function updateAvailability($id, Request $request)
    {


        $user = User::findOrFail($id);

        $request->validate([
            'availability' => 'required|boolean',
        ]);

        $user->update([
            'availability' => $request->input('availability'),
        ]);
        $user = User::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'message' => 'User availability updated successfully.',
            'user' => $user,
        ]);
    }


    public function getAvailableUser(Request $request)
    {
        $status = $request->post('status');
        $areaCode = $request->post('area_code');

        // Determine the role based on the status
        $role = ($status === 'Claim Submitted') ? 'Surveyor' : 'Adjustor';

        // Find an available user based on the role and area code
        $user = User::where('area_pincode', $areaCode)
            ->whereHas('roles', function ($query) use ($role) {
                $query->where('name', $role);
            })
            // ->whereDoesntHave('claims', function ($query) use ($status) {
            //     $query->where('claim_status', $status);
            // })
            ->first();

        if ($user) {
            return response()->json(['user_id' => $user->id]);
        } else {
            return response()->json(['message' => 'No available user found'], 404);
        }
    }


    // You can add more methods based on your requirements, e.g., show, store, update, destroy.
}
