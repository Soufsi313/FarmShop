@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Mon Panier') }}</div>

                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-4"></i>
                        <h3>Votre panier est vide</h3>
                        <p class="text-muted">Commencez vos achats en ajoutant des produits à votre panier.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>
                            Découvrir nos produits
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
