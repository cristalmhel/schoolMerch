<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <section class="login-card">
        <h2>Welcome Back</h2>
        <p>Sign in to your account</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label class="text-white" for="student-id">Student Email</label>
                <input type="email" name="email" required autofocus autocomplete="username" placeholder="Enter your student email">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
            </div>

            <!-- Password -->
            <div class="form-group">
                <label class="text-white" for="password">Password</label>
                <input type="password" 
                    id="password" 
                    name="password"  
                    required 
                    autocomplete="current-password" 
                    placeholder="Enter your password">

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <button type="submit" class="login-btn">Login</button>
        </form>
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot">Forgot Password?</a>
        @endif

        @if (Route::has('register'))
            <div class="signup">
                Don’t have an account? <a href="{{ route('register') }}">Sign Up</a>
            </div>
        @endif
    </section>
</x-guest-layout>
