<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800">📚 Exam Portal</h1>
            <div class="flex gap-3">
                @auth
                    @php
                        $dashboardRoute = match(auth()->user()->role) {
                            'admin' => 'admin.dashboard',
                            'lecturer' => 'lecturer.dashboard',
                            default => 'student.dashboard',
                        };
                    @endphp
                    <a href="{{ route($dashboardRoute) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 font-semibold py-2 px-4">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-6 py-20 text-center">
            <h2 class="text-5xl font-bold text-gray-800 mb-4">Online Exam Portal</h2>
            <p class="text-gray-500 text-lg mb-8 max-w-xl mx-auto">A simple and secure platform for lecturers to create exams and students to take them.</p>
            @guest
                <div class="flex gap-4 justify-center">
                    <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg">
                        Get Started
                    </a>
                    <a href="{{ route('login') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 px-8 rounded-lg text-lg">
                        Login
                    </a>
                </div>
            @endguest
        </div>
    </div>

    {{-- Stats --}}
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                <p class="text-4xl font-bold text-blue-500">{{ $stats['classes'] }}</p>
                <p class="text-gray-600 mt-2">Classes</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                <p class="text-4xl font-bold text-green-500">{{ $stats['subjects'] }}</p>
                <p class="text-gray-600 mt-2">Subjects</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                <p class="text-4xl font-bold text-purple-500">{{ $stats['exams'] }}</p>
                <p class="text-gray-600 mt-2">Active Exams</p>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-6 text-center">
                <p class="text-4xl font-bold text-yellow-500">{{ $stats['students'] }}</p>
                <p class="text-gray-600 mt-2">Students</p>
            </div>
        </div>
    </div>

    {{-- How it works --}}
    <div class="bg-white border-t border-b">
        <div class="max-w-7xl mx-auto px-6 py-16">
            <h3 class="text-2xl font-bold text-gray-800 text-center mb-12">How It Works</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-5xl mb-4">🎓</div>
                    <h4 class="font-bold text-lg mb-2">Register</h4>
                    <p class="text-gray-500">Create an account and apply to join a class of your choice.</p>
                </div>
                <div>
                    <div class="text-5xl mb-4">📝</div>
                    <h4 class="font-bold text-lg mb-2">Take Exams</h4>
                    <p class="text-gray-500">Access exams assigned to your class and submit your answers before time runs out.</p>
                </div>
                <div>
                    <div class="text-5xl mb-4">📊</div>
                    <h4 class="font-bold text-lg mb-2">Get Results</h4>
                    <p class="text-gray-500">View your results instantly for MCQ and after lecturer grading for open text.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="text-center py-8 text-gray-400 text-sm">
        Exam Portal © {{ date('Y') }}
    </footer>

</body>
</html>