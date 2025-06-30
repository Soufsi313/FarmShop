@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Mes Locations') }}</div>

                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-calendar-check fa-4x text-muted mb-4"></i>
                        <h3>Aucune location</h3>
                        <p class="text-muted">Vous n'avez pas encore loué d'équipement.</p>
                        <a href="{{ route('products.index', ['type' => 'rental']) }}" class="btn btn-primary">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Explorer les locations
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
