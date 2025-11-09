@extends('layouts.sidebar')

@section('content')
<div class="p-6 bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">User Details</h2>
            <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-700 font-medium px-4 py-2 border border-blue-600 rounded-lg transition">
                ← Back to Users
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">First Name</p>
                <p class="font-medium text-gray-800">{{ $user->firstname }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Middle Name</p>
                <p class="font-medium text-gray-800">{{ $user->middlename ?? '—' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Last Name</p>
                <p class="font-medium text-gray-800">{{ $user->lastname }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium text-gray-800">{{ $user->email }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Role</p>
                <p class="font-medium text-gray-800 capitalize">{{ $user->role }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">School ID</p>
                <p class="font-medium text-gray-800">{{ $user->school_id }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Contact Number</p>
                <p class="font-medium text-gray-800">{{ $user->contact_number }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Address</p>
                <p class="font-medium text-gray-800">{{ $user->address }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Course</p>
                <p class="font-medium text-gray-800">{{ $user->course }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">School Year</p>
                <p class="font-medium text-gray-800">{{ $user->school_year }}</p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Department</p>
                <p class="font-medium text-gray-800">{{ $user->department }}</p>
            </div>
        </div>

        <div class="flex justify-end space-x-3 mt-8">
            <a href="{{ route('users.edit', $user->id) }}" 
               class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                Edit
            </a>
            <button 
                type="button" 
                onclick="confirmDelete({{ $user->id }}, '{{ $user->school_id }}')"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg shadow font-medium transition">
                Delete
            </button>
        </div>
    </div>
</div>
{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Product</h3>
            <div class="mt-2 px-7 py-3">
                <p id="modal-text" class="text-sm text-gray-500">
                    Are you sure you want to delete the product? This action cannot be undone.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                {{-- Form for DELETE request --}}
                <form id="deleteForm" method="POST" action="" class="inline w-full"> 
                    @csrf
                    @method('DELETE')
                    
                    {{-- Use Flexbox to align and space the buttons --}}
                    <div class="flex justify-between space-x-4"> 
                        <button type="button" onclick="closeModal()" class="flex-1 px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                            Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const modal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const modalText = document.getElementById('modal-text');

    function confirmDelete(userId, school_id) {
        // 1. Set the action URL for the form to the correct route
        // Assuming your base route is /users/{id}
        const url = `/users/${userId}`; 
        deleteForm.action = url;

        // 2. Update the modal text
        modalText.innerHTML = `Are you sure you want to delete the user **${school_id}**? This action cannot be undone.`;

        // 3. Show the modal
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }
    
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === modal) {
            closeModal();
        }
    }
</script>
@endsection
