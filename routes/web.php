<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController; 
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DepartmentController; 
use App\Http\Controllers\ProfileRequestController; 
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController; 

// ==========================================
// Root Route (Login k baad initial redirect)
// ==========================================
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        
        if ($role == 'student') {
            return redirect()->route('student.dashboard');
        } elseif ($role == 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Auth::routes();

// ==========================================
// Home Route (Auth default redirect)
// ==========================================
Route::get('/home', [HomeController::class, 'index'])->name('home');

// ==========================================
// Admin & Superadmin Dashboard
// ==========================================
Route::get('/dashboard', [AdminController::class, 'dashboard'])
    ->name('dashboard')
    ->middleware(['auth', 'role:admin,super_admin']); 

// ==========================================
// Teacher Dashboard (Separate)
// ==========================================
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/teacher/dashboard', [TeacherController::class, 'dashboard'])->name('teacher.dashboard');
    Route::get('/teacher/notifications', [TeacherController::class, 'notifications'])->name('teacher.notifications');
});

// ==========================================
// Subjects Management (Admin & Super Admin)
// ==========================================
Route::middleware(['auth', 'role:admin,super_admin'])->group(function () {
    Route::resource('subjects', SubjectController::class);
    Route::post('/subjects/assign', [SubjectController::class, 'assignTeacher'])->name('subjects.assign');
    
    // NEW: Remove Teacher Route
    Route::delete('/subjects/{subject}/remove-teacher/{user}', [SubjectController::class, 'removeTeacher'])->name('subjects.removeTeacher');
});

// ==========================================
// Shared Routes (Admin, Teacher, SuperAdmin)
// ==========================================
Route::middleware(['auth', 'role:admin,teacher,super_admin'])->group(function () {
    
    Route::resource('departments', DepartmentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('students', StudentController::class);

    Route::get('/get-courses/{id}', [DepartmentController::class, 'getCourses'])->name('get.courses');

    // --- NEW ROUTE ADDED HERE ---
    // Ye route subjects ko department aur semester ke hisaab se fetch karega
    Route::get('/subjects/by-dept-sem/{dept_id}/{semester}', [SubjectController::class, 'getByDeptSem'])->name('subjects.byDeptSem');

    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    
    Route::get('/profile-requests', [AdminController::class, 'requests'])->name('admin.requests');
    Route::get('/create-profile/{id}', [ProfileRequestController::class, 'createProfile'])->name('create.profile');
    
    Route::delete('/delete-request/{id}', [AdminController::class, 'deleteRequest'])->name('delete.request');
    Route::delete('/profile-request/{id}', [AdminController::class, 'destroyProfileRequest'])->name('admin.profile-request.destroy');
    
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications/store', [NotificationController::class, 'store'])->name('notifications.store');
    
    Route::get('/notifications/history', [NotificationController::class, 'history'])->name('notifications.history');
    Route::delete('/notifications/history/{id}', [NotificationController::class, 'destroyHistory'])->name('notifications.history.destroy');

    Route::get('/leave-requests', [AdminController::class, 'viewLeaves'])->name('admin.leaves');
    Route::get('/leave-approve/{id}', [AdminController::class, 'approveLeave'])->name('admin.approveLeave');
    Route::get('/leave-reject/{id}', [AdminController::class, 'rejectLeave'])->name('admin.rejectLeave');
    Route::delete('/leave-delete/{id}', [AdminController::class, 'deleteLeave'])->name('admin.deleteLeave');
});

// ==========================================
// Student Routes
// ==========================================
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/my-dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
    Route::get('/my-attendance', [StudentController::class, 'myAttendance'])->name('student.attendance');
    Route::get('/my-notifications', [NotificationController::class, 'studentNotifications'])->name('student.notifications');
    Route::post('/send-profile-request', [StudentController::class, 'sendRequest'])->name('student.request');
    
    Route::get('/get-courses/{id}', [DepartmentController::class, 'getCourses']);

    Route::get('/my-leave', [StudentController::class, 'leaveForm'])->name('student.leave');
    Route::post('/submit-leave', [StudentController::class, 'submitLeave'])->name('student.submitLeave');
});

// ==========================================
// Activity Logs (Only Super Admin)
// ==========================================
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::delete('/activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
});