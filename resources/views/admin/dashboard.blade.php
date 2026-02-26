<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('admin.users.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="font-bold text-lg mb-2">👥 User Management</h3>
                    <p class="text-gray-600">Create and manage lecturers, students and admins.</p>
                </a>
                <a href="{{ route('admin.classes.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="font-bold text-lg mb-2">🏫 Class Management</h3>
                    <p class="text-gray-600">Create classes and enroll students.</p>
                </a>
                <a href="{{ route('admin.subjects.index') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:bg-gray-50">
                    <h3 class="font-bold text-lg mb-2">📚 Subject Management</h3>
                    <p class="text-gray-600">Create subjects and assign lecturers.</p>
                </a>
                
            </div>
        </div>
    </div>
</x-app-layout>