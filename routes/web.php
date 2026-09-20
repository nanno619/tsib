<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ExportUsersPdfController;
use App\Http\Controllers\Settings\UpdateAvatarController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Models\Activity;

Route::redirect('/', '/dashboard');

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::view('/settings/profile', 'settings.profile')->name('settings.profile');
    Route::put('/settings/avatar', UpdateAvatarController::class)->name('settings.avatar.update');
    Route::view('/settings/password', 'settings.password')->name('settings.password');

    Route::get('/settings/activity', function () {
        return view('settings.activity', [
            'activities' => Activity::where('causer_id', auth()->id())
                ->latest()
                ->paginate(10),
        ]);
    })->name('settings.activity');

    Route::get('/starter-kit', function () {
        return view('starter-kit', [
            'users' => User::orderBy('name')->paginate(5, pageName: 'users_page'),
        ]);
    })->name('starter-kit');

    Route::get('/starter-kit/users.pdf', ExportUsersPdfController::class)->name('starter-kit.users-pdf');

    // Admin area. Authenticated here; authorization is per-action via the
    // #[Authorize] attributes on the controller, which resolve against
    // App\Policies\UserPolicy. A failing check throws 403, rendered by
    // resources/views/errors/403.blade.php.
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');

    // These have to precede /admin/users/{user}: registered after it, "create"
    // matches as an id and the model binding 404s.
    Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');

    Route::get('/admin/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users/{user}/reset-password', [UserController::class, 'sendPasswordReset'])->name('admin.users.reset-password');
});
