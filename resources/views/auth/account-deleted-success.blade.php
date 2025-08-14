@extends('layouts.app')

@section('title', 'Compte supprimé avec succès')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-check-circle"></i>
                        Compte supprimé avec succès
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-user-times fa-3x text-success mb-3"></i>
                        <h5>Votre compte a été définitivement supprimé</h5>
                    </div>
                    
                    <div class="alert alert-success">
                        <h6><i class="fas fa-download"></i> Téléchargement de vos données</h6>
                        <p class="mb-0">
                            Conformément au RGPD, un fichier ZIP contenant toutes vos données 
                            a été automatiquement téléchargé.
                        </p>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6>Ce fichier contient :</h6>
                        <ul class="text-left mb-0">
                            <li>Vos informations de profil (PDF)</li>
                            <li>Historique de vos commandes (PDF)</li>
                            <li>Historique de vos locations (PDF)</li>
                            <li>Vos messages et communications (PDF)</li>
                            <li>Données de navigation et préférences (PDF)</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-muted">
                            Merci d'avoir utilisé FarmShop. Au revoir !
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-home"></i> Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($zipFileName))
<script>
// Déclencher le téléchargement automatique du ZIP
document.addEventListener('DOMContentLoaded', function() {
    const link = document.createElement('a');
    link.href = '{{ asset('storage/gdpr/') }}/' + '{{ $zipFileName }}';
    link.download = '{{ $zipFileName }}';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});

// Auto-déconnexion après 5 secondes
setTimeout(function() {
    window.location.href = "{{ route('logout') }}";
}, 5000);
</script>
@else
<script>
// Auto-déconnexion après 5 secondes même sans ZIP
setTimeout(function() {
    window.location.href = "{{ route('logout') }}";
}, 5000);
</script>
@endif
@endsection