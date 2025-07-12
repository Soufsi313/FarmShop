FARMSHOP - RÉPONSE À VOTRE MESSAGE DE CONTACT
=============================================

Référence : {{ $contactReference }}

Bonjour {{ $contact->name }},

Nous vous remercions d'avoir pris contact avec nous. Voici notre réponse à votre message concernant : {{ $contact->subject }}

NOTRE RÉPONSE
-------------
{{ $contact->admin_response }}

@if($adminName)
— {{ $adminName }}, Équipe FarmShop
@endif

Répondu le {{ $contact->responded_at->format('d/m/Y à H:i') }}

VOTRE MESSAGE ORIGINAL ({{ $contact->created_at->format('d/m/Y à H:i') }})
--------------------------------------------------------------------------
Raison : {{ $contact->reason_label }}
Objet : {{ $contact->subject }}

{{ $contact->message }}

BESOIN D'AIDE SUPPLÉMENTAIRE ?
------------------------------
Si vous avez d'autres questions, n'hésitez pas à nous recontacter :

📧 Email : s.mef2703@gmail.com
🌐 Site web : {{ $websiteUrl }}
📋 Formulaire de contact : {{ $websiteUrl }}/contact

---
FARMSHOP - Votre partenaire pour l'agriculture moderne
Vente et location de matériel agricole de qualité

Ce message a été envoyé en réponse à votre demande de contact.
Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer ce message.
