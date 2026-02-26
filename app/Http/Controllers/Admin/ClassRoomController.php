<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassRoomController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::withCount('students')->get();
        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('admin.classes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:classes,code',
            'description' => 'nullable|string',
        ]);

        ClassRoom::create($request->only('name', 'code', 'description'));

        return redirect()->route('admin.classes.index')->with('success', 'Class created successfully!');
    }

    public function edit(ClassRoom $class)
    {
        $subjects = Subject::all();
        $lecturers = User::where('role', 'lecturer')->get();
        $classSubjects = $class->classSubjects()->with(['subject', 'lecturer'])->get();

        return view('admin.classes.edit', compact('class', 'subjects', 'lecturers', 'classSubjects'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:classes,code,' . $class->id,
            'description' => 'nullable|string',
        ]);

        $class->update($request->only('name', 'code', 'description'));

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully!');
    }

    public function destroy(ClassRoom $class)
    {
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully!');
    }

    // Assign a subject and lecturer to a class
    public function assignSubject(Request $request, ClassRoom $class)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'lecturer_id' => 'required|exists:users,id',
        ]);

        // Check if subject already assigned to this class
        $exists = $class->classSubjects()
                        ->where('subject_id', $request->subject_id)
                        ->exists();

        if ($exists) {
            return back()->with('error', 'This subject is already assigned to this class!');
        }

        $class->classSubjects()->create([
            'subject_id' => $request->subject_id,
            'lecturer_id' => $request->lecturer_id,
        ]);

        return back()->with('success', 'Subject assigned successfully!');
    }

    // Remove a subject from a class
    public function removeSubject(ClassRoom $class, $classSubjectId)
    {
        $class->classSubjects()->where('id', $classSubjectId)->delete();
        return back()->with('success', 'Subject removed successfully!');
    }

    // Assign a student to a class
    public function assignStudent(Request $request, ClassRoom $class)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
        ]);
        
        $student = User::find($request->student_id);

        if ($student->class_id) {
            return back()->with('error', 'Student is already assigned to a class!');
        }
        
        $student->update(['class_id' => $class->id]);
        return back()->with('success', 'Student assigned successfully!');
    }

    // Remove a student from a class
    public function removeStudent(ClassRoom $class, User $student)
    {
        $student->update(['class_id' => null]);
        return back()->with('success', 'Student removed successfully!');
    }
}