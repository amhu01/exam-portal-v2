<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Class Enrollment
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                    {{ session('info') }}
                </div>
            @endif

            {{-- My Requests --}}
            @if($requests->count() > 0)
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold text-lg mb-4">My Requests</h3>
                @foreach($requests as $req)
                <div class="border rounded p-4 mb-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-semibold">{{ $req->classRoom->name }}</p>
                            <p class="text-gray-500 text-sm">{{ $req->classRoom->code }}</p>
                            @if($req->admin_remarks)
                                <p class="text-red-600 text-sm mt-1">Remarks: {{ $req->admin_remarks }}</p>
                            @endif
                        </div>
                        <span class="px-2 py-1 rounded text-sm font-semibold
                            {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $req->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $req->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                        ">
                            {{ ucfirst($req->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Available Classes --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold text-lg mb-4">Available Classes</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @forelse($classes as $class)
                    <div class="border rounded p-4">
                        <h4 class="font-bold">{{ $class->name }}</h4>
                        <p class="text-gray-500 text-sm">{{ $class->code }}</p>
                        <p class="text-gray-600 text-sm mt-1">{{ $class->description ?? 'No description.' }}</p>
                        <p class="text-gray-500 text-sm mt-1">{{ $class->students_count }} students enrolled</p>

                        @php
                            $alreadyRequested = $requests->where('class_id', $class->id)
                                                         ->whereIn('status', ['pending', 'approved'])
                                                         ->first();
                        @endphp

                        <div class="mt-3">
                            @if($alreadyRequested)
                                <span class="text-gray-500 text-sm">Already requested</span>
                            @else
                                <a href="{{ route('student.enrollment.create', $class) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                    Apply
                                </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500">No classes available yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>