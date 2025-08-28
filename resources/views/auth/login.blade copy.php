<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Estate CME-I</title>
    @vite('resources/css/app.css')
    <style>
        .input-field {
            border-bottom: 2px solid #e5e7eb;
            transition: border-color 0.3s ease;
        }
        .input-field:focus {
            border-color: #3b82f6;
            outline: none;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="bg-white rounded-lg shadow-sm w-full max-w-xs p-8">
        <!-- Header -->
        <h1 class="text-2xl font-semibold text-gray-800 mb-8 text-center">Estate CME-I Application</h1>

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Username/Email Field -->
            <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">Email</label>
                <input type="text" name="email" required autofocus
                    class="w-full py-2 input-field text-gray-800"
                    placeholder="">
            </div>

            <!-- Password Field -->
            <div class="space-y-1">
                <label class="text-sm font-medium text-gray-500">Password</label>
                <input type="password" name="password" required
                    class="w-full py-2 input-field text-gray-800"
                    placeholder="">
            </div>

            <!-- Sign In Button -->
            <button type="submit"
                class="w-full py-2 bg-gray-800 text-white rounded-md
                       hover:bg-gray-700 transition duration-200">
                Log in
            </button>
        </form>
    </div>

</body>
</html>
</html>