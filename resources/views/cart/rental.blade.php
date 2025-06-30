@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Panier Location') }}</div>

                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-calendar-alt fa-4x text-muted mb-4"></i>
                        <h3>Votre panier location est vide</h3>
                        <p class="text-muted">Commencez à louer du matériel agricole.</p>
                        <a href="{{ route('products.index', ['type' => 'rental']) }}" class="btn btn-primary">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Voir les locations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
