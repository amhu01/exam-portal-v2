<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Student Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(!auth()->user()->class_id)
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                    You are not enrolled in any class yet.
                    <a href="{{ route('student.enrollment.index') }}" class="font-bold underline ml-1">Browse classes and apply here.</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white shadow-sm sm:rounded-lg p-6">
                        <h3 class="font-bold text-lg mb-2">🏫 My Class</h3>
                        <p class="text-gray-600">{{ auth()->user()->classRoom->name }}</p>
                        <p class="text-gray-500 text-sm">{{ auth()->user()->classRoom->code }}</p>
                    </div>
                    {{-- <a href="{{ route('student.exams.index') }}" class="bg-white shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                        <h3 class="font-bold text-lg mb-2">📝 My Exams</h3>
                        <p class="text-gray-600">View and take your assigned exams.</p>
                    </a> --}}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>