<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Auth - FarmShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation Test -->
    @include('components.navigation')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">🔍 Debug Authentification</div>
                    <div class="card-body">
                        <h5>Statut de connexion :</h5>
                        
                        @auth
                            <div class="alert alert-success">
                                <h6>✅ Vous êtes connecté !</h6>
                                <p><strong>Nom :</strong> {{ Auth::user()->name }}</p>
                                <p><strong>Email :</strong> {{ Auth::user()->email }}</p>
                                <p><strong>Username :</strong> {{ Auth::user()->username ?? 'Non défini' }}</p>
                                <p><strong>Email vérifié :</strong> {{ Auth::user()->email_verified_at ? 'Oui ✅' : 'Non ❌' }}</p>
                                <p><strong>Rôles :</strong> {{ Auth::user()->roles->pluck('name')->implode(', ') }}</p>
                                <p><strong>Admin :</strong> {{ Auth::user()->hasRole('admin') ? 'Oui ✅' : 'Non ❌' }}</p>
                            </div>
                            
                            <h6>Test de déconnexion :</h6>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Se déconnecter
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning">
                                <h6>❌ Vous n'êtes PAS connecté</h6>
                                <p>Vous devez vous connecter pour voir cette section.</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
