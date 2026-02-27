<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lecturer Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('lecturer.exams.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="font-bold text-lg mb-2">📝 My Exams</h3>
                    <p class="text-gray-600">Create and manage your exams.</p>
                </a>
                <a href="{{ route('lecturer.grading.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="font-bold text-lg mb-2">✏️ Grading</h3>
                    <p class="text-gray-600">Grade student exam submissions.</p>
                </a>                
            </div>
        </div>
    </div>
</x-app-layout>
