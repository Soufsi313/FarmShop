<!DOCTYPE html>
<html>
<head>
    <title>Test Translation</title>
</head>
<body>
    <h1>Test des traductions de produits</h1>
    
    @php
        $product = \App\Models\Product::where('slug', 'tondeuse-autoportee-agricole')->first();
    @endphp
    
    @if($product)
        <h2>Produit: {{ $product->slug }}</h2>
        
        <h3>Locale actuelle: {{ app()->getLocale() }}</h3>
        
        <h3>Test avec trans_product():</h3>
        <ul>
            <li>Nom FR: {{ trans_product($product, 'name') }}</li>
        </ul>
        
        @php
            app()->setLocale('en');
        @endphp
        <h3>Après setLocale('en'): {{ app()->getLocale() }}</h3>
        <ul>
            <li>Nom EN: {{ trans_product($product, 'name') }}</li>
        </ul>
        
        @php
            app()->setLocale('nl');
        @endphp
        <h3>Après setLocale('nl'): {{ app()->getLocale() }}</h3>
        <ul>
            <li>Nom NL: {{ trans_product($product, 'name') }}</li>
        </ul>
    @else
        <p>Produit non trouvé</p>
    @endif
</body>
</html>
