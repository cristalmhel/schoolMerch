@extends('layouts.sidebar')

@section('content')
<x-alert type="success" />
<x-alert type="error" />
<div class="p-2">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manage Users</h1>
            <p class="text-gray-500">Manage, view, and delete users in your application.</p>
        </div>
    </div>

    <hr class="my-4">

    <form method="GET" action="{{ route('users.index') }}" class="space-y-4">

        <!-- ✅ Filter Bar -->
        <div class="flex flex-wrap items-center gap-3 bg-white p-4 rounded-lg shadow mb-6">
            <input 
                type="text" 
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or ID" 
                class="w-96 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200"
            >
            
            <select name="department" class="w-48 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
                <option value="">All Departments</option>
                @foreach(config('constants.college_departments') as $key => $department)
                    <option value="{{ $key }}">{{ $department }}</option>
                @endforeach
            </select>

            <select name="sort" class="w-40 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:ring-blue-200">
                <option value="">Sort By</option>
                <option value="lname_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Last Name (A-Z)</option>
                <option value="lname_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Last Name (Z-A)</option>
                <option value="fname_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>First Name (A-Z)</option>
                <option value="fname_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>First Name (Z-A)</option>
                <option value="s_id_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>ID (ASC)</option>
                <option value="s_id_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>ID (DESC)</option>
            </select>

            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-medium px-12 py-2 rounded-lg shadow transition duration-200">
                Filter
            </button>
           
        </div>

        <!-- ✅ Users Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-3"><input type="checkbox" id="select-all"></th>
                        <th class="p-3">ID</th>
                        <th class="p-3">First Name</th>
                        <th class="p-3">Last Name</th>
                        <th class="p-3">Course</th>
                        <th class="p-3">Department</th>
                        <th class="p-3">Year</th>
                        <th class="p-3">Role</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y>
                    @forelse ($users as $user)
                        <tr class="text-sm">
                            <td class="p-3">
                                <input type="checkbox" name="selected_users[]" value="{{ $user->id }}">
                            </td>
                            <td class="p-3 font-semibold">{{ $user->school_id }}</td>
                            <td class="p-3 font-semibold">{{ $user->firstname }}</td>
                            <td class="p-3 font-semibold">{{ $user->lastname }}</td>
                            <td class="p-3">{{ $user->course }}</td>
                            <td class="p-3">{{ $user->department ?? '-' }}</td>
                            <td class="p-3">{{ $user->school_year ?? '-' }}</td>
                            <td class="p-3">{{ $user->role ?? '-' }}</td>
                            <td class="p-1 text-center space-x-2">
                                <a href="{{ route('users.show', $user->id) }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-2 py-1 rounded text-sm">View</a>
                                <a href="{{ route('users.edit', $user->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-sm">Edit</a>
                                <button 
                                    type="button" 
                                    onclick="confirmDelete({{ $user->id }}, '{{ $user->school_id }}')"
                                    class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-sm font-medium transition">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-4 text-center text-gray-500">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-4">
                {{ $users->appends(request()->query())->links('pagination::tailwind') }}
            </div>
        </div>
    </form>

    <!-- JS for select all -->
    <script>
    document.getElementById('select-all')?.addEventListener('change', function(e) {
        document.querySelectorAll('input[name="selected_users[]"]').forEach(cb => cb.checked = e.target.checked);
    });
    </script>

</div>

{{-- Delete Confirmation Modal --}}
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Delete User</h3>
            <div class="mt-2 px-7 py-3">
                <p id="modal-text" class="text-sm text-gray-500">
                    Are you sure you want to delete the user? This action cannot be undone.
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
        // Assuming your base route is /products/{id}
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
