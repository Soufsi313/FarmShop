@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Ma Wishlist') }}</div>

                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-heart fa-4x text-muted mb-4"></i>
                        <h3>Votre wishlist est vide</h3>
                        <p class="text-muted">Ajoutez vos produits favoris à votre liste de souhaits.</p>
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
