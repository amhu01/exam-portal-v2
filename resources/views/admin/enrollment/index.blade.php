<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Enrollment Requests
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                @forelse($requests as $req)
                <div class="border rounded p-4 mb-4" x-data="{ showReject: false }">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <p class="font-bold">{{ $req->student->name }}</p>
                                <span class="text-gray-500 text-sm">{{ $req->student->email }}</span>
                                <span class="px-2 py-1 rounded text-sm font-semibold
                                    {{ $req->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $req->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $req->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                ">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm">Applying for: <span class="font-semibold">{{ $req->classRoom->name }}</span></p>
                            <p class="text-gray-600 text-sm mt-2">{{ $req->statement }}</p>

                            @if($req->document_path)
                                <a href="{{ Storage::url($req->document_path) }}" target="_blank"
                                    class="text-blue-500 hover:underline text-sm mt-1 inline-block">
                                    📄 View Document
                                </a>
                            @endif

                            @if($req->admin_remarks)
                                <p class="text-red-600 text-sm mt-2">Remarks: {{ $req->admin_remarks }}</p>
                            @endif
                        </div>

                        @if($req->isPending())
                        <div class="flex flex-col gap-2 ml-4">
                            <form method="POST" action="{{ route('admin.enrollment.approve', $req) }}">
                                @csrf
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm w-full">
                                    Approve
                                </button>
                            </form>
                            <button @click="showReject = !showReject"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                Reject
                            </button>
                        </div>
                        @endif
                    </div>

                    {{-- Reject form --}}
                    @if($req->isPending())
                    <div x-show="showReject" class="mt-4 border-t pt-4">
                        <form method="POST" action="{{ route('admin.enrollment.reject', $req) }}">
                            @csrf
                            <label class="block text-gray-700 font-bold mb-2">Reason for Rejection</label>
                            <textarea name="admin_remarks" rows="3"
                                class="w-full border rounded px-3 py-2 mb-2"></textarea>
                            @error('admin_remarks') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Confirm Rejection
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
                @empty
                <p class="text-gray-500 text-center">No enrollment requests yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>