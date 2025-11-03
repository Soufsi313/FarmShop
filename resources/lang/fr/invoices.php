<?php

return [
    // En-têtes
    'invoice_title' => 'Facture de Location',
    'initial_invoice_title' => 'FACTURE INITIALE',
    'final_invoice_title' => 'FACTURE DÉFINITIVE',
    'invoice_number' => 'Facture N°',
    'rental_number' => 'N° Location',
    'inspection_date' => 'Date inspection',
    'invoice_date' => 'Date de facture',
    'due_date' => 'Date d\'échéance',
    'status_paid' => 'Payé',
    'status_pending' => 'En attente',
    'status_partially_paid' => 'Partiellement payé',
    'status_failed' => 'Échec',
    
    // Informations client
    'bill_to' => 'Facturé à',
    'client_info' => 'Informations client',
    'phone' => 'Téléphone',
    'email' => 'Email',
    'not_provided' => 'Non renseigné',
    
    // Informations location
    'rental_period' => 'Période de Location',
    'rental_start_date' => 'Date de début',
    'rental_end_date' => 'Date de fin',
    'rental_duration' => 'Durée',
    'daily_tariff' => 'Tarif journalier',
    'rental_details' => 'Détails de la location',
    'status' => 'Statut',
    'payment_method' => 'Mode de paiement',
    'payment_reference' => 'Référence de paiement',
    'confirmation_date' => 'Date de confirmation',
    'start_date' => 'Date de début effectif',
    'end_date' => 'Date de fin effective',
    'day_singular' => 'jour',
    'days_plural' => 'jours',
    'per_day' => '/jour',
    'initial_invoice_note' => 'Cette facture sera mise à jour après inspection du matériel retourné.',
    'final_invoice_note' => 'Facture définitive après inspection du matériel retourné.',
    'deposit_fully_released' => 'La caution de :amount € a été intégralement libérée.',
    'deposit_partially_released' => ':refund € de la caution ont été libérés. :retained € ont été retenus pour les frais.',
    'deposit_fully_retained' => 'La caution de :amount € a été entièrement retenue pour couvrir les frais.',
    'deposit_processing' => 'La caution de :amount € est en cours de traitement.',
    'note' => 'Note',
    'deposit_colon' => 'Caution',
    'ref_label' => 'Réf',
    
    // Tableau produits
    'product' => 'Produit',
    'quantity' => 'Qté',
    'unit_price' => 'Prix unitaire',
    'daily_rate' => 'Prix/jour',
    'days' => 'Jours',
    'deposit' => 'Caution',
    'total' => 'Total',
    'reference' => 'Réf',
    
    // Totaux
    'subtotal_ht' => 'Sous-total HT',
    'subtotal_rental_ht' => 'Sous-total location HT',
    'shipping_cost' => 'Frais de livraison',
    'discount' => 'Remise',
    'total_deposit' => 'Caution totale',
    'vat' => 'TVA',
    'late_penalties' => 'Pénalités de retard',
    'damage_fees' => 'Frais de dommages',
    'total_ttc' => 'TOTAL TTC',
    'deposit_released' => 'Caution libérée',
    
    // Statuts
    'status_pending' => 'En attente',
    'status_confirmed' => 'Confirmée',
    'status_active' => 'En cours',
    'status_completed' => 'Terminée',
    'status_cancelled' => 'Annulée',
    'status_inspecting' => 'En inspection',
    'status_finished' => 'Terminée',
    
    // Méthodes de paiement
    'payment_card' => 'Carte bancaire',
    'payment_transfer' => 'Virement',
    'payment_cash' => 'Espèces',
    'payment_stripe' => 'Carte bancaire',
    
    // Messages de paiement
    'payment_completed' => 'Paiement effectué',
    'payment_completed_message' => 'Le paiement de cette facture a été effectué le :date par :name via :method.',
    'payment_completed_rental_message' => 'Le paiement de cette facture de location a été effectué le :date par :name via :method.',
    'payment_pending' => 'Paiement en attente',
    'payment_pending_message' => 'Cette facture est en attente de paiement. Merci de procéder au règlement dans les meilleurs délais.',
    'payment_pending_rental_message' => 'Cette facture de location est en attente de paiement. Merci de procéder au règlement dans les meilleurs délais.',
    
    // Inspection
    'inspection_notes' => 'Notes d\'inspection',
    'inspection_performed' => 'Inspection réalisée le',
    'at' => 'à',
    
    // Conditions
    'sale_conditions' => 'Conditions de vente',
    'sale_conditions_text' => 'Les produits alimentaires ne sont pas échangeables ni remboursables pour des raisons d\'hygiène et de sécurité alimentaire. Les produits non alimentaires peuvent être retournés dans un délai de 14 jours après livraison, dans leur emballage d\'origine et en parfait état. Les frais de retour sont à la charge du client sauf en cas de défaut du produit.',
    'rental_conditions' => 'Conditions de location',
    'rental_conditions_text' => 'Le matériel loué doit être retourné dans l\'état où il a été livré. Tout dommage ou perte sera facturé au prix de remplacement. En cas de retard de restitution, des pénalités de :penalty_rate €/jour seront appliquées. La caution pré-autorisée sera libérée dans un délai de 7 jours ouvrés après inspection du matériel, ou capturée en cas de dommages.',
    
    // Entreprise
    'company_title' => 'Matériel agricole - Achat et location',
    'vat_number' => 'N° TVA',
    'phone_label' => 'Tél',
    'email_label' => 'Email',
    
    // En-tête facture commande
    'invoice' => 'FACTURE',
    'order_number' => 'N° Commande',
    'billing_address' => 'ADRESSE DE FACTURATION',
    'shipping_address' => 'ADRESSE DE LIVRAISON',
    'customer_info' => 'Informations client',
    'client' => 'Client',
    'order_details' => 'Détails de la commande',
    'tracking_number' => 'N° de suivi',
    'shipping_date' => 'Date d\'expédition',
    'delivery_date' => 'Date de livraison',
    'invoice_generated' => 'Facture générée automatiquement le',
    
    // Jours
    'day' => 'jour',
    'days_plural' => 'jours',
];
