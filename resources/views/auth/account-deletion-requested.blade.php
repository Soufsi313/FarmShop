@extends('layouts.app')

@section('title', 'Suppression de compte demandée')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Suppression de compte demandée
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-envelope fa-3x text-warning mb-3"></i>
                        <h5>Un email de confirmation vous a été envoyé</h5>
                    </div>
                    
                    <div class="alert alert-info">
                        <p class="mb-2">
                            <strong>Vérifiez votre boîte email</strong> {{ auth()->user()->email }}
                        </p>
                        <p class="mb-0">
                            Cliquez sur le lien de confirmation pour finaliser la suppression de votre compte.
                        </p>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Important :</h6>
                        <ul class="text-left mb-0">
                            <li>Le lien expire dans <strong>60 minutes</strong></li>
                            <li>Cette action est <strong>irréversible</strong></li>
                            <li>Toutes vos données seront définitivement supprimées</li>
                            <li>Vous recevrez un fichier ZIP avec vos données (GDPR)</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour au tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection