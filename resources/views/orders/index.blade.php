@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Mes Commandes') }}</div>

                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-box fa-4x text-muted mb-4"></i>
                        <h3>Aucune commande</h3>
                        <p class="text-muted">Vous n'avez pas encore passé de commande.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Commencer mes achats
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
