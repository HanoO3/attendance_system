<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProfileRequestController extends Controller
{
   public function createProfile($id)
{
    $profileRequest = ProfileRequest::find($id);

    if (!$profileRequest) {
        return redirect()->back()->with('error', 'Request not found.');
    }

    return redirect()->route('students.create', [
        'name'       => $profileRequest->user->name ?? '',
        'email'      => $profileRequest->user->email ?? '',
        'request_id' => $profileRequest->id
    ]);
}

public function destroy($id)
{
    $request = ProfileRequest::find($id);
    
    if ($request) {
        $request->delete();
        return back()->with('success', 'Request deleted successfully.');
    }

    return back()->with('error', 'Request not found.');
}
}