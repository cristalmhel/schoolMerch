<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\User::query();

        // 🔍 Search
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                ->orWhere('school_id', 'like', "%{$search}%")
                ->orWhere('lastname', 'like', "%{$search}%");
            });
        }

        // 🏫 Department
        if ($department = $request->input('department')) {
            $query->where('department', $department);
        }

        // 🔤 Sorting
        $sortOptions = [
            'lname_asc'  => ['lastname', 'asc'],
            'lname_desc' => ['lastname', 'desc'],
            'fname_asc'  => ['firstname', 'asc'],
            'fname_desc' => ['firstname', 'desc'],
            's_id_asc'   => ['school_id', 'asc'],
            's_id_desc'  => ['school_id', 'desc'],
        ];

        // Get selected sort option from request
        $sort = $request->input('sort');

        // Apply sorting if valid, otherwise default
        if (array_key_exists($sort, $sortOptions)) {
            [$column, $direction] = $sortOptions[$sort];
            $query->orderBy($column, $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        $users = $query->paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|string',
            'school_id' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'school_year' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('users.show', $user->id)
                        ->with('success', 'User information updated successfully.');
    }

    /**
     * Delete User
     */
    public function destroy($id)
    {
        // 1. Find the user
        $user = User::findOrFail($id);
        
        // 3. Delete the user record from the database
        $user->delete();

        // 4. Redirect back to the user list with a success message
        return redirect()->route('users.index')
                        ->with('success', 'User "' . $user->school_id . '" deleted successfully.');
    }
}
