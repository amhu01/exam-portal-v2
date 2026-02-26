<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                My Exams
            </h2>
            <a href="{{ route('lecturer.exams.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create Exam
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-3">Title</th>
                                <th class="pb-3">Subject</th>
                                <th class="pb-3">Class</th>
                                <th class="pb-3">Time Limit</th>
                                <th class="pb-3">Questions</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exams as $exam)
                            <tr class="border-b">
                                <td class="py-3">{{ $exam->title }}</td>
                                <td class="py-3">{{ $exam->classSubject->subject->name }}</td>
                                <td class="py-3">{{ $exam->classSubject->classRoom->name }}</td>
                                <td class="py-3">{{ $exam->time_limit }} mins</td>
                                <td class="py-3">{{ $exam->questions_count }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-1 rounded text-sm font-semibold {{ $exam->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $exam->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('lecturer.exams.questions', $exam) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                            Questions
                                        </a>
                                        <a href="{{ route('lecturer.exams.edit', $exam) }}" class="bg-yellow-400 hover:bg-yellow-500 text-white font-bold py-1 px-3 rounded text-sm">
                                            Edit
                                        </a>
                                        <form method="POST" action="{{ route('lecturer.exams.togglePublish', $exam) }}">
                                            @csrf
                                            <button type="submit" class="bg-{{ $exam->is_published ? 'gray' : 'green' }}-500 hover:bg-{{ $exam->is_published ? 'gray' : 'green' }}-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                {{ $exam->is_published ? 'Unpublish' : 'Publish' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('lecturer.exams.destroy', $exam) }}" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-3 text-center text-gray-500">No exams yet. Create your first exam!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
