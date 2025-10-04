<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#e9ebf6] font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div class="circle red"></div>
            <div class="circle blue"></div>
            <div class="circle green"></div>
            <div class="circle orange"></div>
            <div class="flex items-center justify-center p-4">
                <div class="bg-white my-4 w-full max-w-2xl p-8 rounded-xl shadow-lg">
                    <div class="flex items-center mb-6 space-x-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-full">
                            <a href="/">
                                <img src="image/hellya.JPG" alt="Hero Logo" 
                                class="hero-logo w-12 h-12 rounded-full object-cover">
                            </a>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">School Merch Registration</h1>
                            <p class="text-sm text-gray-500">Sign up to access exclusive school merchandise.</p>
                        </div>
                    </div>

                    <form class="space-y-4" method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-input-label for="firstname" :value="__('First Name')" />
                                <x-text-input id="firstname" class="w-full" type="text" name="firstname" :value="old('firstname')" required autofocus autocomplete="firstname" />
                                <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="middlename" :value="__('Middle Name')" />
                                <x-text-input id="middlename" class="w-full" type="text" name="middlename" :value="old('middlename')" autofocus autocomplete="middlename" />
                                <x-input-error :messages="$errors->get('middlename')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="lastname" :value="__('Last Name')" />
                                <x-text-input id="lastname" class="w-full" type="text" name="lastname" :value="old('lastname')" required autofocus autocomplete="lastname" />
                                <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
                                <p class="text-xs text-gray-500 mt-1">We'll never share your email.</p>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="contact_number" :value="__('Contact Number')" />
                                <x-text-input id="contact_number" class="w-full" type="text" placeholder="09xxxxxxxxx" name="contact_number" :value="old('contact_number')" required autofocus autocomplete="contact_number" />
                                <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Password --}}
                            <div x-data="{ show: false }">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                                    Password
                                </label>
                                <div class="relative">
                                    <input
                                        id="password"
                                        name="password"
                                        :type="show ? 'text' : 'password'"
                                        required
                                        autocomplete="new-password"
                                        class="w-full border border-gray-300 rounded-lg p-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    >

                                    {{-- Toggle button --}}
                                    <button type="button"
                                        @click="show = !show"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none"
                                        tabindex="-1">
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.964 9.964 0 012.642-4.419M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>

                                @error('password')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div x-data="{ show: false }" >
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                                    Confirm Password
                                </label>

                                <div class="relative">
                                    <input
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        :type="show ? 'text' : 'password'"
                                        required
                                        autocomplete="new-password"
                                        class="w-full border border-gray-300 rounded-lg p-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    >

                                    {{-- Toggle button --}}
                                    <button type="button"
                                        @click="show = !show"
                                        class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-gray-700 focus:outline-none"
                                        tabindex="-1">
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.964 9.964 0 012.642-4.419M15 12a3 3 0 11-6 0 3 3 0 016 0zM3 3l18 18" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="school_id" :value="__('School ID')" />
                                <x-text-input id="school_id" class="w-full" type="text" placeholder="202312345" name="school_id" :value="old('school_id')" required autofocus autocomplete="school_id" />
                                <x-input-error :messages="$errors->get('school_id')" class="mt-2" />
                            </div>
                            <div>
                                <label for="course" class="block text-sm font-medium text-gray-700">
                                    Course
                                </label>
                                <select
                                    id="course"
                                    name="course"
                                    required
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                >
                                    <option value="" disabled selected hidden>Select Course</option>
                                    <option value="bsit">BS Information Technology</option>
                                    <option value="bsba">BS Business Administration</option>
                                    <option value="bsee">BS Electrical Engineering</option>
                                    <option value="bsed">BS Education</option>
                                </select>
                                <x-input-error :messages="$errors->get('course')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <textarea id="address" name="address" rows="3" class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Year</label>
                                <select
                                    id="school_year"
                                    name="school_year"
                                    required
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                >
                                    <option value="" disabled selected hidden>Select Year</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                    <option value="5">5th Year</option>
                                </select>
                                <x-input-error :messages="$errors->get('school_year')" class="mt-2" />
                            </div>
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">College Department</label>
                                <select
                                    id="department"
                                    name="department"
                                    required
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                >
                                    <option value="" disabled selected hidden>Select Department</option>
                                    <option value="College of Computer Studies">College of Computer Studies</option>
                                    <option value="College of Computer Engineering">College of Computer Engineering</option>
                                    <option value="College of Criminology">College of Criminology</option>
                                    <option value="College of Education">College of Education</option>
                                </select>
                                <x-input-error :messages="$errors->get('department')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-end gap-4 pt-4">
                            <x-primary-button type="submit">
                                Create Account
                            </x-primary-button>
                        </div>

                        <p class="text-center text-xs text-gray-500 mt-4">
                            By registering, you agree to the 
                            <a href="#" class="text-blue-600 hover:underline">Terms</a> &
                            <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>.
                        </p>
                        <p class="text-center text-xs text-gray-500 mt-4">
                            Already registered? 
                            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</html>
