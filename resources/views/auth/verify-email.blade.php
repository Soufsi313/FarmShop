@extends('layouts.app')

@section('title', 'Vérification de l\'email')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-farm-green-50 to-farm-orange-50">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-farm-green-100">
                <svg class="h-6 w-6 text-farm-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Vérification de votre email
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Un email de vérification a été envoyé à votre adresse
            </p>
        </div>

        <div class="mt-8 space-y-6">
            <div class="bg-white/80 backdrop-blur-sm p-6 rounded-lg shadow-lg border border-farm-green-200">
                @if (session('message'))
                    <div class="mb-4 p-4 bg-farm-green-100 border border-farm-green-300 text-farm-green-700 rounded-lg">
                        {{ session('message') }}
                    </div>
                @endif

                <div class="text-center">
                    <p class="text-gray-700 mb-6">
                        Merci de vous être inscrit ! Avant de commencer, pourriez-vous vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer ? Si vous n'avez pas reçu l'email, nous vous en enverrons volontiers un autre.
                    </p>

                    <div class="space-y-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-farm-green-600 hover:bg-farm-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-farm-green-500 transition-colors">
                                📧 Renvoyer l'email de vérification
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex justify-center py-2 px-4 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                                Se déconnecter
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Vous n'avez pas reçu l'email ? Vérifiez vos spams ou 
                    <a href="{{ route('contact') }}" class="font-medium text-farm-orange-600 hover:text-farm-orange-700 transition-colors">
                        contactez-nous
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
