@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Nous Contacter') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations de contact</h5>
                            <p><i class="fas fa-envelope me-2"></i> contact@farmshop.be</p>
                            <p><i class="fas fa-phone me-2"></i> +32 2 123 45 67</p>
                            <p><i class="fas fa-map-marker-alt me-2"></i> Rue de l'Agriculture 123, 1000 Bruxelles</p>
                            
                            <h5 class="mt-4">Horaires d'ouverture</h5>
                            <p>Lundi - Vendredi : 8h00 - 18h00<br>
                               Samedi : 9h00 - 16h00<br>
                               Dimanche : Fermé</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Nous écrire</h5>
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Envoyer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
