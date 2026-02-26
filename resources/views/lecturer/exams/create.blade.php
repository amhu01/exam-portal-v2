<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create Exam
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('lecturer.exams.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Title</label>
                            <input type="text" name="title" value="{{ old('title') }}"
                                class="w-full border rounded px-3 py-2 @error('title') border-red-500 @enderror">
                            @error('title') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Description</label>
                            <textarea name="description" rows="3"
                                class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Class & Subject</label>
                            <select name="class_subject_id" class="w-full border rounded px-3 py-2 @error('class_subject_id') border-red-500 @enderror">
                                <option value="">Select class and subject</option>
                                @foreach($classSubjects as $classSubject)
                                    <option value="{{ $classSubject->id }}" {{ old('class_subject_id') == $classSubject->id ? 'selected' : '' }}>
                                        {{ $classSubject->classRoom->name }} — {{ $classSubject->subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_subject_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2">Time Limit (minutes)</label>
                            <input type="number" name="time_limit" value="{{ old('time_limit', 15) }}" min="1" max="180"
                                class="w-full border rounded px-3 py-2 @error('time_limit') border-red-500 @enderror">
                            @error('time_limit') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Exam
                            </button>
                            <a href="{{ route('lecturer.exams.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
