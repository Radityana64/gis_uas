<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Register</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen">
        <div class="w-full max-w-md p-8 space-y-8 bg-white shadow-lg rounded-lg">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900">Register</h2>
            </div>
            <form action="{{ route('register.save') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input name="name" id="name" type="text" placeholder="Name" class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 text-gray-700" />
                    @error('name')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input name="email" id="email" type="email" placeholder="Email Address" class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 text-gray-700" />
                    @error('email')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input name="password" id="password" type="password" placeholder="Enter Password" class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 text-gray-700" />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <svg id="toggle-password" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.978 3.057-4.768 5-9.542 5-4.478 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                    @error('password')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <div class="relative">
                        <input name="password_confirmation" id="password_confirmation" type="password" placeholder="Confirm Password" class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50 text-gray-700" />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <svg id="toggle-password-confirmation" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cursor-pointer" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.978 3.057-4.768 5-9.542 5-4.478 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                    </div>
                    @error('password_confirmation')
                    <span class="text-red-600">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <button type="submit" class="w-full px-4 py-2 text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Register Account</button>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Already have an account? <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Login</a></p> 
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('toggle-password').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('eye-open');
        });

        document.getElementById('toggle-password-confirmation').addEventListener('click', function() {
            const passwordInput = document.getElementById('password_confirmation');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('eye-open');
        });
    </script>
    <style>
        .eye-open {
            filter: invert(50%);
        }
    </style>
    @vite('resources/js/app.js')
</body>
</html>
