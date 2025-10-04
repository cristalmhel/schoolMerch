<x-guest-layout>
    <div class="flex items-center justify-center">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md border border-blue-200 text-center">
            <h2 class="text-2xl font-bold mb-4">Check Your Email</h2>
            <p class="text-gray-600 mb-6">
                If an account exists for the email you entered, you will receive a password reset link shortly.
            </p>

            <a href="{{ url('/') }}" 
            class="inline-block bg-slate-600 hover:bg-slate-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                Go to Homepage
            </a>
        </div>
    </div>
</x-guest-layout>
s