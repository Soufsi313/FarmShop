NOUVEAU MESSAGE DE CONTACT - FARMSHOP
=========================================

Reçu le {{ $contact->created_at->format('d/m/Y à H:i') }}

INFORMATIONS DU CONTACT
-----------------------
Nom : {{ $contact->name }}
Email : {{ $contact->email }}
@if($contact->phone)
Téléphone : {{ $contact->phone }}
@endif
Raison : {{ $contact->reason_label }}
Priorité : {{ $contact->priority_label }}

OBJET DU MESSAGE
----------------
{{ $contact->subject }}

MESSAGE
-------
{{ $contact->message }}

ACTIONS
-------
Voir le contact : {{ $contactUrl }}
Dashboard Admin : {{ $adminDashboardUrl }}

@if($contact->metadata)
MÉTADONNÉES TECHNIQUES
----------------------
@if(isset($contact->metadata['ip_address']))
Adresse IP : {{ $contact->metadata['ip_address'] }}
@endif
@if(isset($contact->metadata['user_agent']))
Navigateur : {{ $contact->metadata['user_agent'] }}
@endif
Référence : CONTACT-{{ str_pad($contact->id, 6, '0', STR_PAD_LEFT) }}
@endif

---
Ce message a été envoyé automatiquement par le système FarmShop.
Pour répondre au visiteur, utilisez l'interface d'administration.
