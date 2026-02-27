<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Submissions — {{ $exam->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @forelse($submissions as $submission)
                <div class="border rounded p-4 mb-4 flex justify-between items-center">
                    <div>
                        <p class="font-bold">{{ $submission->student->name }}</p>
                        <p class="text-gray-500 text-sm">{{ $submission->student->email }}</p>
                        <p class="text-gray-500 text-sm">Submitted: {{ $submission->submitted_at->format('d M Y, h:i A') }}</p>
                        <span class="px-2 py-1 rounded text-sm font-semibold mt-1 inline-block
                            {{ $submission->isSubmitted() ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                            {{ $submission->isSubmitted() ? 'Needs Grading' : 'Graded' }}
                        </span>
                    </div>
                    <div class="text-right">
                        @if($submission->isGraded())
                            <p class="font-bold text-green-600">{{ $submission->total_marks }} / {{ $exam->totalMarks() }}</p>
                        @endif
                        <a href="{{ route('lecturer.grading.show', [$exam, $submission]) }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 inline-block">
                            {{ $submission->isSubmitted() ? 'Grade' : 'Review' }}
                        </a>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center">No submissions yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>