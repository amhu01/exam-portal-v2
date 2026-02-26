<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manage Class: {{ $class->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Edit Class Info --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-4">Class Info</h3>
                    <form method="POST" action="{{ route('admin.classes.update', $class) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Name</label>
                            <input type="text" name="name" value="{{ old('name', $class->name) }}"
                                class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Code</label>
                            <input type="text" name="code" value="{{ old('code', $class->code) }}"
                                class="w-full border rounded px-3 py-2 @error('code') border-red-500 @enderror">
                            @error('code') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Description</label>
                            <textarea name="description" rows="3"
                                class="w-full border rounded px-3 py-2">{{ old('description', $class->description) }}</textarea>
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Update Class
                        </button>
                    </form>
                </div>
            </div>

            {{-- Assign Subjects --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-4">Subjects & Lecturers</h3>

                    {{-- Assigned subjects list --}}
                    @forelse($classSubjects as $classSubject)
                    <div class="flex justify-between items-center border-b py-3">
                        <div>
                            <span class="font-semibold">{{ $classSubject->subject->name }}</span>
                            <span class="text-gray-500 text-sm ml-2">({{ $classSubject->subject->code }})</span>
                            <span class="text-gray-600 text-sm ml-4">👤 {{ $classSubject->lecturer->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('admin.classes.removeSubject', [$class, $classSubject->id]) }}" onsubmit="return confirm('Remove this subject?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Remove
                            </button>
                        </form>
                    </div>
                    @empty
                    <p class="text-gray-500 mb-4">No subjects assigned yet.</p>
                    @endforelse

                    {{-- Assign new subject form --}}
                    <form method="POST" action="{{ route('admin.classes.assignSubject', $class) }}" class="mt-4 flex gap-3 items-end">
                        @csrf
                        <div class="flex-1">
                            <label class="block text-gray-700 font-bold mb-2">Subject</label>
                            <select name="subject_id" class="w-full border rounded px-3 py-2">
                                <option value="">Select subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-700 font-bold mb-2">Lecturer</label>
                            <select name="lecturer_id" class="w-full border rounded px-3 py-2">
                                <option value="">Select lecturer</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Assign
                        </button>
                    </form>
                </div>
            </div>

            {{-- Manage Students --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-4">Students</h3>

                    {{-- Current students --}}
                    @forelse($class->students as $student)
                    <div class="flex justify-between items-center border-b py-3">
                        <div>
                            <span class="font-semibold">{{ $student->name }}</span>
                            <span class="text-gray-500 text-sm ml-2">{{ $student->email }}</span>
                        </div>
                        <form method="POST" action="{{ route('admin.classes.removeStudent', [$class, $student]) }}" onsubmit="return confirm('Remove this student?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Remove
                            </button>
                        </form>
                    </div>
                    @empty
                    <p class="text-gray-500 mb-4">No students enrolled yet.</p>
                    @endforelse

                    {{-- Assign student form --}}
                    <form method="POST" action="{{ route('admin.classes.assignStudent', $class) }}" class="mt-4 flex gap-3 items-end">
                        @csrf
                        <div class="flex-1">
                            <label class="block text-gray-700 font-bold mb-2">Add Student</label>
                            <select name="student_id" class="w-full border rounded px-3 py-2">
                                <option value="">Select student</option>
                                @foreach(\App\Models\User::where('role', 'student')->whereNull('class_id')->get() as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Enroll
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>