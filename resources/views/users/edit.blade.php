@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Modifier l'utilisateur</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Name Input -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <!-- Email Input -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </div>

        <!-- Role Selection -->
        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
            <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection