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
                        Nouveau mot de passe
                    </h2>

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
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Adresse e-mail
                            </label>
                            <input 
                                id="email" 
                                class="form-control" 
                                type="email" 
                                name="email" 
                                value="{{ old('email', $request->email) }}" 
                                required 
                                autofocus 
                                autocomplete="email"
                                readonly
                            />
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Nouveau mot de passe
                            </label>
                            <input 
                                id="password" 
                                class="form-control" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="new-password"
                            />
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Confirmer le mot de passe
                            </label>
                            <input 
                                id="password_confirmation" 
                                class="form-control" 
                                type="password" 
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                            />
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-lock me-1"></i>
                                Réinitialiser le mot de passe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
