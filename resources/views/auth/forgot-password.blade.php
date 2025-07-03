<x-guest-layout>
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-4">
        <div class="w-100" style="max-width: 400px;">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <!-- Logo -->
                    <div class="text-center mb-4">
                        <a href="{{ route('welcome') }}" class="h3 text-success text-decoration-none fw-bold">
                            🌱 FarmShop
                        </a>
                    </div>

                    <!-- Titre -->
                    <h2 class="h4 mb-4 text-center text-dark">
                        Mot de passe oublié
                    </h2>

                    <!-- Description -->
                    <div class="mb-4 text-muted small">
                        Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous votre adresse e-mail et nous vous enverrons un lien de réinitialisation qui vous permettra d'en choisir un nouveau.
                    </div>

                    <!-- Message de succès -->
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Erreurs de validation -->
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <strong>Quelque chose s'est mal passé.</strong>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulaire -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Adresse e-mail
                            </label>
                            <input 
                                id="email" 
                                class="form-control" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="email"
                            />
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a class="text-decoration-none text-muted" href="{{ route('login') }}">
                                <i class="fas fa-arrow-left me-1"></i>
                                Retour à la connexion
                            </a>

                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-1"></i>
                                Envoyer le lien
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
