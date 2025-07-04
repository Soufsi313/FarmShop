@extends('layouts.app')

@section('title', 'Finaliser ma commande')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Finaliser ma commande</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf
                        
                        <!-- Section Adresse de livraison -->
                        <div class="mb-4">
                            <h5 class="mb-3">Adresse de livraison</h5>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="shipping_address" class="form-label">Adresse *</label>
                                    <input type="text" class="form-control @error('shipping_address') is-invalid @enderror" 
                                           id="shipping_address" name="shipping_address" 
                                           value="{{ old('shipping_address') }}" required>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_city" class="form-label">Ville *</label>
                                    <input type="text" class="form-control @error('shipping_city') is-invalid @enderror" 
                                           id="shipping_city" name="shipping_city" 
                                           value="{{ old('shipping_city') }}" required>
                                    @error('shipping_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_postal_code" class="form-label">Code postal *</label>
                                    <input type="text" class="form-control @error('shipping_postal_code') is-invalid @enderror" 
                                           id="shipping_postal_code" name="shipping_postal_code" 
                                           value="{{ old('shipping_postal_code') }}" required>
                                    @error('shipping_postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="shipping_country" class="form-label">Pays</label>
                                    <select class="form-select" id="shipping_country" name="shipping_country">
                                        <option value="France" {{ old('shipping_country', 'France') == 'France' ? 'selected' : '' }}>France</option>
                                        <option value="Belgique" {{ old('shipping_country') == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                                        <option value="Suisse" {{ old('shipping_country') == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                                        <option value="Luxembourg" {{ old('shipping_country') == 'Luxembourg' ? 'selected' : '' }}>Luxembourg</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section Récapitulatif de la commande -->
                        <div class="mb-4">
                            <h5 class="mb-3">Récapitulatif de votre commande</h5>
                            
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Prix unitaire</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $subtotal = 0; @endphp
                                        @foreach($cartItems as $item)
                                            @php $subtotal += $item->total_price; @endphp
                                            <tr>
                                                <td>{{ $item->product ? $item->product->name : 'Produit supprimé' }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->unit_price, 2) }} €</td>
                                                <td>{{ number_format($item->total_price, 2) }} €</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Sous-total :</strong></td>
                                            <td><strong>{{ number_format($subtotal, 2) }} €</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end"><strong>Frais de livraison :</strong></td>
                                            <td><strong>
                                                @if($subtotal >= 25)
                                                    Gratuit
                                                @else
                                                    2,50 €
                                                @endif
                                            </strong></td>
                                        </tr>
                                        <tr class="table-success">
                                            <td colspan="3" class="text-end"><strong>Total :</strong></td>
                                            <td><strong>{{ number_format($subtotal + ($subtotal >= 25 ? 0 : 2.50), 2) }} €</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('cart.index') }}" class="btn btn-secondary me-md-2">Retour au panier</a>
                            <button type="submit" class="btn btn-success">Valider ma commande</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
