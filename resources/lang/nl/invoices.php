<?php

return [
    // Headers
    'invoice_title' => 'Huurvergoeding',
    'initial_invoice_title' => 'INITIËLE FACTUUR',
    'final_invoice_title' => 'DEFINITIEVE FACTUUR',
    'invoice_number' => 'Factuur nr.',
    'rental_number' => 'Huur nr.',
    'inspection_date' => 'Inspectiedatum',
    'invoice_date' => 'Factuurdatum',
    'due_date' => 'Vervaldatum',
    'status_paid' => 'Betaald',
    'status_pending' => 'In afwachting',
    'status_partially_paid' => 'Gedeeltelijk betaald',
    'status_failed' => 'Mislukt',
    
    // Client information
    'bill_to' => 'Factureren aan',
    'client_info' => 'Klantinformatie',
    'phone' => 'Telefoon',
    'email' => 'E-mail',
    'not_provided' => 'Niet verstrekt',
    
    // Rental information
    'rental_period' => 'Huurperiode',
    'rental_start_date' => 'Startdatum',
    'rental_end_date' => 'Einddatum',
    'rental_duration' => 'Duur',
    'daily_tariff' => 'Dagtarief',
    'rental_details' => 'Verhuurdetails',
    'status' => 'Status',
    'payment_method' => 'Betaalmethode',
    'payment_reference' => 'Betalingsreferentie',
    'confirmation_date' => 'Bevestigingsdatum',
    'start_date' => 'Werkelijke startdatum',
    'end_date' => 'Werkelijke einddatum',
    'day_singular' => 'dag',
    'days_plural' => 'dagen',
    'per_day' => '/dag',
    'initial_invoice_note' => 'Deze factuur wordt bijgewerkt na inspectie van de geretourneerde apparatuur.',
    'final_invoice_note' => 'Definitieve factuur na inspectie van de geretourneerde apparatuur.',
    'deposit_fully_released' => 'De borg van :amount € is volledig vrijgegeven.',
    'deposit_partially_released' => ':refund € van de borg is vrijgegeven. :retained € is ingehouden voor kosten.',
    'deposit_fully_retained' => 'De borg van :amount € is volledig ingehouden om de kosten te dekken.',
    'deposit_processing' => 'De borg van :amount € wordt verwerkt.',
    'note' => 'Opmerking',
    'deposit_colon' => 'Borg',
    'ref_label' => 'Ref',
    
    // Product table
    'product' => 'Product',
    'quantity' => 'Aantal',
    'unit_price' => 'Eenheidsprijs',
    'daily_rate' => 'Dagprijs',
    'days' => 'Dagen',
    'deposit' => 'Borg',
    'total' => 'Totaal',
    'reference' => 'Ref',
    
    // Totals
    'subtotal_ht' => 'Subtotaal excl. BTW',
    'subtotal_rental_ht' => 'Huur subtotaal excl. BTW',
    'shipping_cost' => 'Verzendkosten',
    'discount' => 'Korting',
    'total_deposit' => 'Totale borg',
    'vat' => 'BTW',
    'late_penalties' => 'Boetes voor te late terugkeer',
    'damage_fees' => 'Schadekosten',
    'total_ttc' => 'TOTAAL incl. BTW',
    'deposit_released' => 'Borg vrijgegeven',
    
    // Statuses
    'status_pending' => 'In afwachting',
    'status_confirmed' => 'Bevestigd',
    'status_active' => 'Actief',
    'status_completed' => 'Voltooid',
    'status_cancelled' => 'Geannuleerd',
    'status_inspecting' => 'Onder inspectie',
    'status_finished' => 'Voltooid',
    
    // Payment methods
    'payment_card' => 'Creditcard',
    'payment_transfer' => 'Bankoverschrijving',
    'payment_cash' => 'Contant',
    'payment_stripe' => 'Creditcard',
    
    // Payment messages
    'payment_completed' => 'Betaling voltooid',
    'payment_completed_message' => 'De betaling voor deze factuur werd voltooid op :date door :name via :method.',
    'payment_completed_rental_message' => 'De betaling voor deze huurfactuur werd voltooid op :date door :name via :method.',
    'payment_pending' => 'Betaling in afwachting',
    'payment_pending_message' => 'Deze factuur wacht op betaling. Ga zo snel mogelijk verder met de betaling.',
    'payment_pending_rental_message' => 'Deze huurfactuur wacht op betaling. Ga zo snel mogelijk verder met de betaling.',
    
    // Inspection
    'inspection_notes' => 'Inspectie notities',
    'inspection_performed' => 'Inspectie uitgevoerd op',
    'at' => 'om',
    
    // Conditions
    'sale_conditions' => 'Verkoopvoorwaarden',
    'sale_conditions_text' => 'Voedingsproducten zijn niet uitwisselbaar of terugbetaalbaar om hygiënische en voedselveiligheidsredenen. Niet-voedingsproducten kunnen binnen 14 dagen na levering worden geretourneerd, in hun originele verpakking en in perfecte staat. Retourkosten zijn voor rekening van de klant, behalve bij een productdefect.',
    'rental_conditions' => 'Huurvoorwaarden',
    'rental_conditions_text' => 'Gehuurd materiaal moet worden teruggebracht in de staat waarin het werd geleverd. Eventuele schade of verlies wordt aangerekend tegen vervangingsprijs. Bij te late terugkeer zijn boetes van :penalty_rate €/dag van toepassing. De vooraf geautoriseerde borg wordt vrijgegeven binnen 7 werkdagen na inspectie van het materiaal, of vastgehouden in geval van schade.',
    
    // Company
    'company_title' => 'Landbouwmateriaal - Koop en huur',
    'vat_number' => 'BTW nr.',
    'phone_label' => 'Tel',
    'email_label' => 'E-mail',
    
    // Invoice header
    'invoice' => 'FACTUUR',
    'order_number' => 'Bestelnr.',
    'billing_address' => 'FACTUURADRES',
    'shipping_address' => 'VERZENDADRES',
    'customer_info' => 'Klantinformatie',
    'client' => 'Klant',
    'order_details' => 'Bestelgegevens',
    'tracking_number' => 'Trackingnummer',
    'shipping_date' => 'Verzenddatum',
    'delivery_date' => 'Leveringsdatum',
    'invoice_generated' => 'Factuur automatisch gegenereerd op',
    
    // Days
    'day' => 'dag',
    'days_plural' => 'dagen',
];
