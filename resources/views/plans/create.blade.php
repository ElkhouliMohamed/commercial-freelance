@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-extrabold text-gray-900 border-b-2 border-gray-200 pb-2 mb-6">Ajouter un Plan</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-2xl rounded-lg overflow-hidden p-6">
            <form action="{{ route('plans.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-sm font-medium text-gray-700">Prix</label>
                    <input type="number" id="price" name="price" step="0.01" value="{{ old('price') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:ring-opacity-50" required>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:ring-opacity-50">{{ old('description') }}</textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg shadow-md hover:bg-gray-700 transition duration-200 ease-in-out transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-opacity-50 flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Enregistrer</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection