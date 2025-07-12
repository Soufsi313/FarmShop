FARMSHOP NEWSLETTER - {{ strtoupper($newsletter->title) }}
=================================================================

Bonjour {{ $user->name }} !

Voici les dernières nouvelles de FarmShop, votre partenaire pour l'agriculture moderne.

Newsletter du {{ $newsletter->created_at->format('d/m/Y') }}

@if($newsletter->excerpt)
RÉSUMÉ
------
{{ $newsletter->excerpt }}
@endif

CONTENU PRINCIPAL
-----------------
{!! strip_tags($newsletter->content) !!}

@if($newsletter->tags && count($newsletter->tags) > 0)
CATÉGORIES
----------
{{ implode(' | ', $newsletter->tags) }}
@endif

LIENS UTILES
------------
🌐 Notre site web : {{ $websiteUrl }}
🛒 Nos produits : {{ $websiteUrl }}/products
📞 Nous contacter : {{ $websiteUrl }}/contact
⚙️ Mes préférences : {{ $preferencesUrl }}

GESTION DE L'ABONNEMENT
-----------------------
Vous recevez cette newsletter car vous êtes abonné(e) à nos actualités.

Pour vous désabonner : {{ $unsubscribeUrl }}
Pour gérer vos préférences : {{ $preferencesUrl }}

---
FARMSHOP - Votre partenaire pour l'agriculture moderne
Vente et location de matériel agricole de qualité

Newsletter envoyée le {{ now()->format('d/m/Y à H:i') }}

Si vous ne souhaitez plus recevoir nos newsletters, cliquez sur le lien de désabonnement ci-dessus.
