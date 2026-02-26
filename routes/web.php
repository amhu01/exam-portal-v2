<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('classes', \App\Http\Controllers\Admin\ClassRoomController::class);
    Route::resource('subjects', \App\Http\Controllers\Admin\SubjectController::class);

    // Extra routes for assigning subjects and students to classes
    Route::post('classes/{class}/subjects', [\App\Http\Controllers\Admin\ClassRoomController::class, 'assignSubject'])->name('classes.assignSubject');
    Route::delete('classes/{class}/subjects/{classSubject}', [\App\Http\Controllers\Admin\ClassRoomController::class, 'removeSubject'])->name('classes.removeSubject');
    Route::post('classes/{class}/students', [\App\Http\Controllers\Admin\ClassRoomController::class, 'assignStudent'])->name('classes.assignStudent');
    Route::delete('classes/{class}/students/{student}', [\App\Http\Controllers\Admin\ClassRoomController::class, 'removeStudent'])->name('classes.removeStudent');

});

Route::middleware(['auth', 'lecturer'])->prefix('lecturer')->name('lecturer.')->group(function () {
    Route::get('/dashboard', function () {
        return view('lecturer.dashboard');
    })->name('dashboard');

    Route::resource('exams', \App\Http\Controllers\Lecturer\ExamController::class);
    Route::post('exams/{exam}/toggle-publish', [\App\Http\Controllers\Lecturer\ExamController::class, 'togglePublish'])->name('exams.togglePublish');
    Route::get('exams/{exam}/questions', [\App\Http\Controllers\Lecturer\QuestionController::class, 'index'])->name('exams.questions');
    Route::post('exams/{exam}/questions', [\App\Http\Controllers\Lecturer\QuestionController::class, 'store'])->name('exams.questions.store');
    Route::delete('exams/{exam}/questions/{question}', [\App\Http\Controllers\Lecturer\QuestionController::class, 'destroy'])->name('exams.questions.destroy');
});

Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
