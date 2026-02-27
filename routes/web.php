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

    Route::get('/enrollment', [\App\Http\Controllers\Admin\EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::post('/enrollment/{enrollmentRequest}/approve', [\App\Http\Controllers\Admin\EnrollmentController::class, 'approve'])->name('enrollment.approve');
    Route::post('/enrollment/{enrollmentRequest}/reject', [\App\Http\Controllers\Admin\EnrollmentController::class, 'reject'])->name('enrollment.reject');
    
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
    
    Route::get('/grading', [\App\Http\Controllers\Lecturer\GradingController::class, 'index'])->name('grading.index');
    Route::get('/grading/{exam}', [\App\Http\Controllers\Lecturer\GradingController::class, 'exam'])->name('grading.exam');
    Route::get('/grading/{exam}/{submission}', [\App\Http\Controllers\Lecturer\GradingController::class, 'show'])->name('grading.show');
    Route::post('/grading/{exam}/{submission}', [\App\Http\Controllers\Lecturer\GradingController::class, 'grade'])->name('grading.grade');    
});

Route::middleware(['auth', 'student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');

    Route::get('/enrollment', [\App\Http\Controllers\Student\EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::get('/enrollment/{class}/apply', [\App\Http\Controllers\Student\EnrollmentController::class, 'create'])->name('enrollment.create');
    Route::post('/enrollment/{class}/apply', [\App\Http\Controllers\Student\EnrollmentController::class, 'store'])->name('enrollment.store');
    
    Route::get('/exams', [\App\Http\Controllers\Student\ExamController::class, 'index'])->name('exams.index');
    Route::get('/exams/{exam}', [\App\Http\Controllers\Student\ExamController::class, 'show'])->name('exams.show');
    Route::post('/exams/{exam}/save', [\App\Http\Controllers\Student\ExamController::class, 'saveAnswer'])->name('exams.save');
    Route::post('/exams/{exam}/submit', [\App\Http\Controllers\Student\ExamController::class, 'submit'])->name('exams.submit');
    Route::get('/exams/{exam}/result', [\App\Http\Controllers\Student\ExamController::class, 'result'])->name('exams.result');
    
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
