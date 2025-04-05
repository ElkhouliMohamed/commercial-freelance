@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8 flex justify-center items-center min-h-screen">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-2xl">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-900 border-b pb-2">Mot de passe oublié ?</h2>

        <p class="mb-4 text-sm text-gray-600">
            Aucun souci ! Indique ton adresse e-mail et nous t’enverrons un lien pour réinitialiser ton mot de passe.
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-600 font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Adresse Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="bg-gray-800 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50">
                    Envoyer le lien
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
