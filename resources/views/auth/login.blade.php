@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8 flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-2xl">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-900 border-b pb-2">Connexion</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                <label for="remember" class="ml-2 text-sm text-gray-700">Se souvenir de moi</label>
            </div>

            <div class="mb-6">
                <button type="submit"
                    class="w-full bg-gray-800 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded shadow-md transition transform hover:-translate-y-1">
                    Connexion
                </button>
            </div>

            @if (Route::has('password.request'))
                <div class="text-center">
                    <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                        Mot de passe oublié ?
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
