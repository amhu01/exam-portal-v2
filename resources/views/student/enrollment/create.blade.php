<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Apply for {{ $class->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 p-4 bg-gray-50 rounded">
                    <h3 class="font-bold">{{ $class->name }}</h3>
                    <p class="text-gray-500 text-sm">{{ $class->code }}</p>
                    <p class="text-gray-600 text-sm mt-1">{{ $class->description ?? 'No description.' }}</p>
                </div>

                <form method="POST" action="{{ route('student.enrollment.store', $class) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">
                            Why do you want to join this class?
                        </label>
                        <p class="text-gray-500 text-sm mb-2">Minimum 50 characters. Explain your interest and qualifications.</p>
                        <textarea name="statement" rows="6"
                            class="w-full border rounded px-3 py-2 @error('statement') border-red-500 @enderror">{{ old('statement') }}</textarea>
                        @error('statement') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">
                            Supporting Document <span class="text-gray-500 font-normal">(optional)</span>
                        </label>
                        <p class="text-gray-500 text-sm mb-2">Upload a transcript, certificate or any relevant document. PDF, DOC, DOCX. Max 2MB.</p>
                        <input type="file" name="document" accept=".pdf,.doc,.docx"
                            class="w-full border rounded px-3 py-2 @error('document') border-red-500 @enderror">
                        @error('document') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Submit Request
                        </button>
                        <a href="{{ route('student.enrollment.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>