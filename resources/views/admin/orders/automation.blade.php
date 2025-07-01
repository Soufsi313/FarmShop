@extends('admin.layout')

@section('title', 'Automatisation des commandes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- En-tête -->
            <div class="mb-4">
                <h1 class="h2 mb-3">Automatisation des commandes</h1>
                <p class="text-muted">Gérez l'automatisation des statuts de commandes et exécutez les tâches manuellement.</p>
            </div>

            <!-- Messages de succès/erreur -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-primary bg-opacity-10 rounded p-2">
                                    <i class="fas fa-clock text-primary fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Commandes en attente</h5>
                                <p class="card-text text-muted">{{ App\Models\Order::where('status', 'pending')->count() }} commandes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-warning bg-opacity-10 rounded p-2">
                                    <i class="fas fa-check-circle text-warning fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Confirmées</h5>
                                <p class="card-text text-muted">{{ App\Models\Order::where('status', 'confirmed')->count() }} commandes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-success bg-opacity-10 rounded p-2">
                                    <i class="fas fa-shipping-fast text-success fs-4"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="card-title mb-1">Expédiées</h5>
                                <p class="card-text text-muted">{{ App\Models\Order::where('status', 'shipped')->count() }} commandes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div>

            <!-- Actions d'automatisation -->
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title mb-0">Actions d'automatisation</h3>
                </div>
                
                <div class="card-body">
                    <!-- Automatisation manuelle -->
                    <div class="border rounded p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="h5 mb-2">Exécuter l'automatisation maintenant</h4>
                                <p class="text-muted mb-2">
                                    Déclenche immédiatement la mise à jour automatique des statuts de toutes les commandes éligibles.
                                </p>
                                <ul class="list-unstyled small text-muted">
                                    <li><i class="fas fa-check text-success me-1"></i> Confirme automatiquement les commandes en attente depuis plus de 24h</li>
                                    <li><i class="fas fa-check text-success me-1"></i> Expédie les commandes confirmées depuis plus de 24h</li>
                                    <li><i class="fas fa-check text-success me-1"></i> Livre les commandes expédiées depuis plus de 3 jours</li>
                                </ul>
                            </div>
                            <div class="col-md-4 text-end">
                                <form action="{{ route('admin.orders.automation.run') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-primary"
                                            onclick="return confirm('Êtes-vous sûr de vouloir exécuter l\'automatisation maintenant ?')">
                                        <i class="fas fa-bolt me-1"></i>
                                        Exécuter maintenant
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                        </div>
                    </div>

                    <!-- Informations sur la planification -->
                    <div class="border rounded p-3 bg-light">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div class="bg-info bg-opacity-10 rounded p-2">
                                    <i class="fas fa-info-circle text-info fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="h5 mb-2">Automatisation planifiée</h4>
                                <p class="text-muted mb-2">
                                    L'automatisation des statuts s'exécute automatiquement <strong>tous les jours à 6h00</strong> via le cron de Laravel.
                                </p>
                                <div class="small text-muted">
                                    <p><strong>Commande cron :</strong> <code class="bg-white px-2 py-1 rounded">php artisan orders:automate-statuses</code></p>
                                    <p class="mt-1"><strong>Planification :</strong> <code class="bg-white px-2 py-1 rounded">$schedule->command('orders:automate-statuses')->dailyAt('06:00')</code></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liens de navigation -->
            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Retour aux commandes
                </a>
                <a href="{{ route('admin.orders.automation.stats') }}" class="btn btn-success">
                    <i class="fas fa-chart-bar me-1"></i> Voir les statistiques
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour actualiser les statistiques via AJAX (optionnel)
function refreshStats() {
    // Ici on pourrait ajouter du JavaScript pour actualiser les stats en temps réel
    location.reload();
}

// Auto-refresh toutes les 30 secondes (optionnel)
// setInterval(refreshStats, 30000);
</script>
@endsection
