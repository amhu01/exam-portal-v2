<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Grading
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @forelse($exams as $exam)
                <div class="border rounded p-4 mb-4 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold">{{ $exam->title }}</h3>
                        <p class="text-gray-500 text-sm">{{ $exam->classSubject->subject->name }} — {{ $exam->classSubject->classRoom->name }}</p>
                        <div class="flex gap-4 mt-1 text-sm">
                            <span class="text-gray-600">{{ $exam->submissions_count }} submissions</span>
                            @if($exam->pending_count > 0)
                                <span class="text-red-600 font-semibold">{{ $exam->pending_count }} pending grading</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('lecturer.grading.exam', $exam) }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        View Submissions
                    </a>
                </div>
                @empty
                <p class="text-gray-500 text-center">No submissions yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>