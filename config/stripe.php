<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe API Keys
    |--------------------------------------------------------------------------
    |
    | Les clés API Stripe pour votre application. Ces clés sont utilisées
    | pour authentifier les requêtes vers l'API Stripe.
    |
    */

    'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
    'secret_key' => env('STRIPE_SECRET_KEY'),
    'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration générale pour Stripe
    |
    */

    'currency' => env('STRIPE_CURRENCY', 'eur'),
    'model' => env('STRIPE_MODEL', App\Models\User::class),

    /*
    |--------------------------------------------------------------------------
    | Test Cards
    |--------------------------------------------------------------------------
    |
    | Cartes de test Stripe pour les environnements de développement
    |
    */

    'test_cards' => [
        'visa_success' => '4242424242424242',
        'visa_declined' => '4000000000000002',
        'visa_insufficient_funds' => '4000000000009995',
        'visa_expired' => '4000000000000069',
        'visa_incorrect_cvc' => '4000000000000127',
        'mastercard_success' => '5555555555554444',
        'american_express' => '378282246310005',
        'mastercard_debit' => '5200828282828210',
        'visa_debit' => '4000058260000005',
        // Cartes 3D Secure
        'visa_3ds_required' => '4000002500003155',
        'visa_3ds_optional' => '4000002760003184',
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Endpoints
    |--------------------------------------------------------------------------
    |
    | Les endpoints pour les webhooks Stripe
    |
    */

    'webhooks' => [
        'payment_intent' => [
            'payment_intent.succeeded',
            'payment_intent.payment_failed',
            'payment_intent.requires_action',
        ],
        'payment_method' => [
            'payment_method.attached',
            'payment_method.detached',
        ],
        'invoice' => [
            'invoice.payment_succeeded',
            'invoice.payment_failed',
        ],
        'subscription' => [
            'customer.subscription.created',
            'customer.subscription.updated',
            'customer.subscription.deleted',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    |
    | Types de méthodes de paiement acceptées
    |
    */

    'payment_methods' => [
        'card',
        'sepa_debit',
        'ideal',
        'bancontact',
        'giropay',
        'sofort',
    ],

    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    |
    | Plans d'abonnement si nécessaire pour les locations récurrentes
    |
    */

    'plans' => [
        'monthly_rental' => env('STRIPE_MONTHLY_RENTAL_PLAN'),
        'weekly_rental' => env('STRIPE_WEEKLY_RENTAL_PLAN'),
    ],

];
