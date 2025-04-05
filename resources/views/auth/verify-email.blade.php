@extends('layouts.app')

@section('content')
<div class="container mx-auto flex justify-center items-center h-screen">
    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Vérification de l'adresse e-mail</h2>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Merci pour votre inscription ! Avant de commencer, veuillez vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer.') }}
            <br>
            {{ __("Si vous ne voyez pas l'e-mail, vérifiez également votre dossier de spam ou de courriers indésirables.") }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('Un nouveau lien de vérification a été envoyé à votre adresse e-mail.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Renvoyer l’e-mail de vérification') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Se déconnecter') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
