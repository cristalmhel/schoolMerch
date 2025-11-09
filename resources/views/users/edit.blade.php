@extends('layouts.sidebar')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Edit User</h2>
            <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-700 font-medium px-4 py-2 border border-blue-600 rounded-lg transition">
                ← Back to Users
            </a>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">First Name</label>
                    <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400" required>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Middle Name</label>
                    <input type="text" name="middlename" value="{{ old('middlename', $user->middlename) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Last Name</label>
                    <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400" required>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400" required>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Role</label>
                    <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400">
                        <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ $user->role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">School ID</label>
                    <input type="text" name="school_id" value="{{ old('school_id', $user->school_id) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Address</label>
                    <input type="text" name="address" value="{{ old('address', $user->address) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Course</label>
                    <select name="course" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400">
                        <option value="" disabled>Select Course</option>
                        <option value="SAO">Student Affairs Office</option>
                        @foreach(config('constants.college_courses') as $key => $course)
                            <option value="{{ $key }}" 
                                    {{ old('course', $user->course) == $key ? 'selected' : '' }}>
                                {{ $course }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">School Year</label>
                    <input type="text" name="school_year" value="{{ old('school_year', $user->school_year) }}" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600 mb-1">Department</label>
                    <select name="department" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:border-blue-400">
                        <option value="" disabled>Select Department</option>
                        <option value="SAO">Student Affairs Office</option>
                        @foreach(config('constants.college_departments') as $key => $department)
                            <option value="{{ $key }}" 
                                    {{ old('course', $user->department) == $key ? 'selected' : '' }}>
                                {{ $department }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-8">
                <button type="submit" 
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
