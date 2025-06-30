@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Blog & Actualités') }}</div>

                <div class="card-body">
                    <div class="text-center">
                        <i class="fas fa-blog fa-4x text-muted mb-4"></i>
                        <h3>Blog en construction</h3>
                        <p class="text-muted">Nous préparons des articles passionnants sur l'agriculture et le matériel agricole.</p>
                        <a href="{{ route('welcome') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>
                            Retour à l'accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
