<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema; // Import Schema
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Notification;
use App\Models\LeaveRequest;
use App\Models\ActivityLog;
use App\Models\ProfileRequest; 
use Illuminate\Pagination\Paginator;  

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Paginator::useBootstrapFive(); 

        View::composer('layouts.app', function ($view) {
            $studentNotifBadge = 0;
            $studentLeaveBadge = 0;
            $teacherNotifBadge = 0;
            $adminActivityBadge = 0;
            $pendingProfileCount = 0;
            $pendingLeavesCount = 0;

            if (Auth::check()) {
                $user = Auth::user();

                if ($user->role == 'student') {
                    $student = Student::where('user_id', $user->id)->first();
                    if ($student) {
                        $studentNotifBadge = Notification::where('user_id', $user->id)
                                                ->where('is_read', false)->count();
                        
                        $studentLeaveBadge = LeaveRequest::where('student_id', $student->id)
                                                ->where('status', 'approved')
                                                ->where('student_seen', false)->count();
                    }
                }

                elseif ($user->role == 'teacher') {
                    $teacherNotifBadge = Notification::where('user_id', $user->id)
                                            ->where('is_read', false)->count();
                }

                elseif (in_array($user->role, ['admin', 'super_admin'])) {
                    
                    $profileQuery = ProfileRequest::where('status', 'pending');
                    if ($user->last_profile_read_at) {
                        $profileQuery->where('created_at', '>', $user->last_profile_read_at);
                    }
                    $pendingProfileCount = $profileQuery->count();

                    $leaveQuery = LeaveRequest::where('status', 'pending');
                    if ($user->last_leave_read_at) {
                        $leaveQuery->where('created_at', '>', $user->last_leave_read_at);
                    }
                    $pendingLeavesCount = $leaveQuery->count();

                    if ($user->role == 'super_admin') {
                        if ($user->last_activity_read_at) {
                            $adminActivityBadge = ActivityLog::where('created_at', '>', $user->last_activity_read_at)->count();
                        } else {
                            $adminActivityBadge = ActivityLog::count();
                        }
                    }
                }
            }

            $view->with('studentNotifBadge', $studentNotifBadge)
                 ->with('studentLeaveBadge', $studentLeaveBadge)
                 ->with('teacherNotifBadge', $teacherNotifBadge)
                 ->with('adminActivityBadge', $adminActivityBadge)
                 ->with('pendingProfileCount', $pendingProfileCount)
                 ->with('pendingLeavesCount', $pendingLeavesCount);
        });
    }
}