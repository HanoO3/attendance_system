<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfileRequest;
use App\Models\Teacher;
use App\Models\Notification;
use App\Models\User;

class ProfileRequestController extends Controller
{
    public function createProfile($id)
    {
        $profileRequest = ProfileRequest::with('user')->find($id);

        if (!$profileRequest) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        if ($profileRequest->type === 'teacher') {

            $teacher = Teacher::where('user_id', $profileRequest->user_id)->first();

            if ($teacher) {
                $teacher->update([
                    'name'          => $profileRequest->teacher_name,
                    'contact'       => $profileRequest->contact,
                    'department_id' => $profileRequest->department_id,
                    'semester'      => $profileRequest->semester,
                    'session'       => $profileRequest->session,
                ]);

                if ($teacher->user) {
                    $teacher->user->update(['name' => $profileRequest->teacher_name]);
                }
            }

            $profileRequest->update(['status' => 'approved']);

            Notification::create([
                'user_id' => $profileRequest->user_id,
                'title'   => 'Profile Setup Complete',
                'message' => 'Your profile has been set up by admin. You can now access your full dashboard.',
                'is_read' => false,
            ]);

            return redirect()->route('admin.requests')
                             ->with('success', 'Teacher profile approved and updated successfully.');
        }

        return redirect()->route('students.create', [
            'name'       => $profileRequest->user->name ?? '',
            'email'      => $profileRequest->user->email ?? '',
            'request_id' => $profileRequest->id,
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