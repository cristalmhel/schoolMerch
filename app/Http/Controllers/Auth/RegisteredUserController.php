<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // ✅ Validate all form fields
        $validated = $request->validate([
            'firstname'        => ['required', 'string', 'max:255'],
            'middlename'       => ['nullable', 'string', 'max:255'],
            'lastname'         => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
            'school_id'        => ['required', 'string', 'max:20'],
            'contact_number'   => ['required', 'string', 'max:20'],
            'address'          => ['required', 'string', 'max:255'],
            'course'           => ['required', 'string', 'max:100'],
            'school_year'      => ['required', 'string', 'max:20'],
            'department'       => ['required', 'string', 'max:100'],
        ]);

        // ✅ Create the new user with all fields and a default role
        $user = User::create([
            'firstname'        => $validated['firstname'],
            'middlename'       => $validated['middlename'] ?? null,
            'lastname'         => $validated['lastname'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'school_id'         => $validated['school_id'],
            'contact_number'    => $validated['contact_number'],
            'address'           => $validated['address'],
            'course'            => $validated['course'],
            'school_year'       => $validated['school_year'],
            'department'        => $validated['department'],
            'role'              => 'student', // default role
        ]);

        // ✅ Fire the Registered event
        event(new Registered($user));

        // ✅ Automatically log in
        Auth::login($user);

        // ✅ Redirect to dashboard or wherever you want
        return redirect()->route('shop.index');
    }
}
