<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Fortify 用（ログイン画面）
|--------------------------------------------------------------------------
*/
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

/*
|--------------------------------------------------------------------------
| 勤怠管理（認証必須）
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () 
{

    Route::get('/attendance', [AttendanceController::class, 'index'])
        ->name('attendance.index');

    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])
        ->name('attendance.clockIn');

    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])
        ->name('attendance.breakStart');

    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])
        ->name('attendance.breakEnd');

    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])
        ->name('attendance.clockOut');



    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');
    Route::get('/attendance/list/{year}/{month}', [AttendanceController::class, 'list'])->name('attendance.list.month');

    // 詳細画面
    Route::get('/attendance/detail/{date}', [AttendanceController::class, 'detail'])->name('attendance.detail');    

    Route::post('/attendance/{id}/correction', 
    [AttendanceController::class, 'requestCorrection'])
    ->name('attendance.requestCorrection')
    ->middleware('auth');

    // 申請一覧（承認待ち／承認済み）
Route::get('/attendance/requests', [AttendanceController::class, 'requestList'])
    ->name('attendance.requestList');

    
});


// 管理者ログイン画面
Route::get('/admin/login', function () {
    return view('admin.login'); // resources/views/admin/login.blade.php
})->name('admin.login');

// 管理者ログイン処理
Route::post('/admin/login', [App\Http\Controllers\Admin\AuthController::class, 'login'])
    ->name('admin.login.post');


Route::middleware(['auth', 'admin'])->group(function() {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');


    // 全ユーザー勤怠一覧
    Route::get('/admin/attendances', [\App\Http\Controllers\AdminAttendanceController::class, 'index'])
        ->name('admin.attendances');

    // 日付変更（前日／翌日）
    Route::get('/admin/attendances/{date}', [\App\Http\Controllers\AdminAttendanceController::class, 'index'])
    ->name('admin.attendances.date');    

    // 全ユーザーの一覧（FN041）
    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])
        ->name('admin.users.index');

    // 指定ユーザーの月次勤怠（FN042）
    Route::get('/admin/users/{user}/attendance/{year}/{month}', 
        [\App\Http\Controllers\AdminAttendanceController::class, 'showUserMonthly'])
        ->name('admin.users.attendance.monthly');    

    // 修正申請一覧
    Route::get('/admin/corrections', [\App\Http\Controllers\Admin\CorrectionController::class, 'index'])
        ->name('admin.corrections.index');

    // 修正申請詳細
    Route::get('/admin/corrections/{id}', [\App\Http\Controllers\Admin\CorrectionController::class, 'show'])
        ->name('admin.corrections.show');

    // 承認処理
    Route::post('/admin/corrections/{id}/approve', [\App\Http\Controllers\Admin\CorrectionController::class, 'approve'])
        ->name('admin.corrections.approve');

    // 却下処理
    Route::post('/admin/corrections/{id}/reject', [\App\Http\Controllers\Admin\CorrectionController::class, 'reject'])
        ->name('admin.corrections.reject');

    Route::get('/admin/attendances/{id}', 
        [\App\Http\Controllers\AdminAttendanceController::class, 'show'])
        ->name('admin.attendance.show');

    Route::post('/admin/attendances/{id}/update',
        [\App\Http\Controllers\AdminAttendanceController::class, 'update'])
        ->name('admin.attendance.update');
        
    // 勤怠詳細
    Route::get('/admin/attendance/{id}', [\App\Http\Controllers\AdminAttendanceController::class, 'show'])
    ->name('admin.attendance.detail');    
});