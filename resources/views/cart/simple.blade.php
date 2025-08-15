@extends('layouts.app')

@section('title', 'Mon Panier - FarmShop')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Mon Panier</h1>

    <div id="cart-content">
        <!-- Le contenu sera chargé ici via JavaScript -->
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-indigo-600 mx-auto"></div>
            <p class="mt-4 text-gray-600">Chargement du panier...</p>
        </div>
    </div>

    <!-- Boutons d'action -->
    <div class="mt-8 flex justify-between">
        <a href="{{ route('products.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Continuer les achats
        </a>
        <div id="cart-actions" style="display: none;">
            <button id="clear-cart" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-4">
                Vider le panier
            </button>
            <a href="{{ route('checkout.index') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Passer commande
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCart();

    // Bouton vider le panier
    document.getElementById('clear-cart').addEventListener('click', function() {
        if (confirm('Êtes-vous sûr de vouloir vider votre panier ?')) {
            clearCart();
        }
    });
});

function loadCart() {
    fetch('/cart/data')
        .then(response => response.json())
        .then(data => {
            displayCart(data);
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('cart-content').innerHTML = 
                '<div class="text-center text-red-600">Erreur lors du chargement du panier</div>';
        });
}

function displayCart(data) {
    const cartContent = document.getElementById('cart-content');
    const cartActions = document.getElementById('cart-actions');

    if (data.items && data.items.length > 0) {
        let html = '<div class="bg-white shadow overflow-hidden sm:rounded-md">';
        html += '<ul class="divide-y divide-gray-200">';

        data.items.forEach(item => {
            html += `
                <li class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-20 w-20">
                            ${item.product_image ? `
                                <img class="h-20 w-20 rounded-lg object-cover" 
                                     src="${item.product_image}" 
                                     alt="${item.product_name}">
                            ` : `
                                <div class="h-20 w-20 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            `}
                        </div>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">${item.product_name}</p>
                                    <div class="text-xs text-gray-500 mt-1">
                                        ${item.special_offer ? `
                                            <div class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs mb-1">
                                                <span class="font-semibold">🔥 ${item.special_offer.title}</span>
                                                <span class="block text-xs">${item.special_offer.discount_percentage}% de réduction</span>
                                            </div>
                                            <div class="space-y-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="line-through text-red-500">Prix normal TTC: ${item.special_offer.original_price_ttc_formatted}</span>
                                                </div>
                                                <div class="text-green-600 font-medium">Prix réduit TTC: ${item.price_per_unit_ttc_formatted}</div>
                                                <div class="text-xs">Prix unitaire HT: ${item.price_per_unit_ht_formatted}</div>
                                                <div class="text-xs">TVA ${item.tax_rate_formatted}: ${(item.price_per_unit_ttc - item.price_per_unit_ht).toFixed(2)} €</div>
                                            </div>
                                        ` : `
                                            <div>Prix unitaire HT: ${item.price_per_unit_ht_formatted}</div>
                                            <div>TVA ${item.tax_rate_formatted}: ${(item.price_per_unit_ttc - item.price_per_unit_ht).toFixed(2)} €</div>
                                            <div class="font-medium">Prix unitaire TTC: ${item.price_per_unit_ttc_formatted}</div>
                                        `}
                                    </div>
                                </div>
                                <div class="text-right ml-4">
                                    <div class="flex items-center justify-end mb-2">
                                        <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" 
                                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-1 px-2 rounded text-sm">-</button>
                                        <span class="mx-3 font-medium min-w-[30px] text-center">${item.quantity}</span>
                                        <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" 
                                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-1 px-2 rounded text-sm">+</button>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">
                                            <div>Sous-total HT: ${item.subtotal_formatted}</div>
                                            <div>TVA: ${item.tax_amount_formatted}</div>
                                            ${item.special_offer ? `
                                                <div class="text-green-600 font-medium">
                                                    Économie: ${item.special_offer.savings_total_ttc_formatted}
                                                </div>
                                            ` : ''}
                                        </div>
                                        <div class="text-lg font-bold text-gray-900">${item.total_formatted}</div>
                                        <button onclick="removeItem(${item.id})" 
                                                class="text-red-600 hover:text-red-800 text-xs mt-1">{{ __("app.buttons.delete") }}<//button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            `;
        });

        html += '</ul></div>';
        
        // Résumé des prix
        html += `
            <div class="mt-6 bg-gray-50 px-6 py-4 rounded-lg">
                <div class="space-y-2">
                    <div class="flex justify-between text-base font-medium text-gray-900">
                        <span>Sous-total HT:</span>
                        <span>${data.subtotal}</span>
                    </div>`;
                    
        // Afficher les détails TVA par taux
        if (data.tva_details && Object.keys(data.tva_details).length > 1) {
            // Panier mixte - afficher détail par taux
            Object.values(data.tva_details).forEach(tvaDetail => {
                html += `
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>TVA ${tvaDetail.rate}% sur ${(tvaDetail.subtotal_ht).toFixed(2)} €:</span>
                        <span>${(tvaDetail.tax_amount).toFixed(2)} €</span>
                    </div>`;
            });
        } else if (data.tva_details && Object.keys(data.tva_details).length === 1) {
            // Un seul taux TVA
            const tvaDetail = Object.values(data.tva_details)[0];
            html += `
                <div class="flex justify-between text-sm text-gray-500">
                    <span>TVA (${tvaDetail.rate}%):</span>
                    <span>${data.tva_amount}</span>
                </div>`;
        } else {
            // Fallback
            html += `
                <div class="flex justify-between text-sm text-gray-500">
                    <span>TVA:</span>
                    <span>${data.tva_amount}</span>
                </div>`;
        }
        
        html += `
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Frais de livraison:</span>
                        <span>${data.is_free_shipping ? 'GRATUIT' : data.shipping_cost}</span>
                    </div>
                    ${!data.is_free_shipping ? `
                    <div class="flex justify-between text-sm text-green-600">
                        <span>Plus que ${data.remaining_for_free_shipping} pour la livraison gratuite !</span>
                        <span></span>
                    </div>
                    ` : ''}`;
                    
        // Calculer les économies totales
        let totalSavings = 0;
        let hasOffers = false;
        data.items.forEach(item => {
            if (item.special_offer && item.special_offer.savings_total_ttc) {
                totalSavings += parseFloat(item.special_offer.savings_total_ttc);
                hasOffers = true;
            }
        });
        
        if (hasOffers) {
            html += `
                    <div class="flex justify-between text-sm text-green-600 font-medium bg-green-50 px-2 py-1 rounded">
                        <span>🎉 Économies totales:</span>
                        <span>-${totalSavings.toFixed(2)} €</span>
                    </div>`;
        }
        
        html += `
                    <div class="border-t pt-2">
                        <div class="flex justify-between text-lg font-bold text-gray-900">
                            <span>Total TTC:</span>
                            <span>${data.total_with_shipping}</span>
                        </div>
                        <div class="text-sm text-gray-500 text-right">
                            ${data.total_items} article(s)
                        </div>
                    </div>
                </div>
            </div>
        `;

        cartContent.innerHTML = html;
        cartActions.style.display = 'block';
    } else {
        cartContent.innerHTML = `
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __("app.messages.cart_empty") }}<//h3>
                <p class="mt-1 text-sm text-gray-500">Votre panier est actuellement vide.</p>
            </div>
        `;
        cartActions.style.display = 'none';
    }
}

function updateQuantity(cartItemId, newQuantity) {
    if (newQuantity < 1) {
        removeItem(cartItemId);
        return;
    }

    fetch('/cart/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            cart_item_id: cartItemId,
            quantity: newQuantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCart();
        }
    })
    .catch(error => console.error('Erreur:', error));
}

function removeItem(cartItemId) {
    fetch('/cart/remove', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            cart_item_id: cartItemId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCart();
        }
    })
    .catch(error => console.error('Erreur:', error));
}

function clearCart() {
    fetch('/cart/clear', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCart();
        }
    })
    .catch(error => console.error('Erreur:', error));
}
</script>
@endsection
