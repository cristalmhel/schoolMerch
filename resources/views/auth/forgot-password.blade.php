<x-guest-layout>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mx-auto w-full max-w-md bg-white p-8 rounded-lg shadow-md border border-blue-200">
        <h2 class="text-2xl font-bold text-center mb-2">Forget Password</h2>
        <p class="text-gray-600 text-center mb-6">Enter your email to reset your password</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Send Reset Link -->
            <button 
                type="submit" 
                class="w-full bg-slate-600 hover:bg-slate-700 text-white font-semibold py-2 rounded-full transition">
                Send Reset Link
            </button>
        </form>

        <!-- Back to Login -->
        <p class="text-center text-gray-600 mt-4">
            Remember your password? 
            <a href="/login" class="text-blue-600 font-semibold hover:underline">Back to Login</a>
        </p>

        <!-- Security Note -->
        <div class="mt-6 p-3 bg-blue-50 rounded-md border text-sm">
            <span class="font-semibold text-gray-800">Security Note:</span>
            <span class="text-gray-600">The reset link will expire in <strong>2 minutes</strong> for your security.</span>
        </div>
    </div>
</x-guest-layout>
