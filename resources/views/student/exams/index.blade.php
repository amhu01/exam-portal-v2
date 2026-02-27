<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Exams
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @forelse($exams as $exam)
                <div class="border rounded p-4 mb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg">{{ $exam->title }}</h3>
                            <p class="text-gray-600 text-sm">{{ $exam->classSubject->subject->name }}</p>
                            <p class="text-gray-500 text-sm">{{ $exam->description }}</p>
                            <div class="flex gap-4 mt-2 text-sm text-gray-600">
                                <span>⏱ {{ $exam->time_limit }} minutes</span>
                                <span>❓ {{ $exam->questions_count }} questions</span>
                                <span>⭐ {{ $exam->totalMarks() }} marks</span>
                            </div>
                        </div>

                        <div class="text-right">
                            @if(isset($submissions[$exam->id]))
                                @php $submission = $submissions[$exam->id]; @endphp

                                @if($submission->isInProgress())
                                    <a href="{{ route('student.exams.show', $exam) }}"
                                        class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                        Continue
                                    </a>
                                @elseif($submission->isSubmitted())
                                    <span class="bg-blue-100 text-blue-700 font-bold py-2 px-4 rounded">
                                        Pending Grading
                                    </span>
                                @elseif($submission->isGraded())
                                    <a href="{{ route('student.exams.result', $exam) }}"
                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                        View Results
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('student.exams.show', $exam) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Start Exam
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center">No exams available yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>