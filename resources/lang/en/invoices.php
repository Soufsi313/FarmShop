<?php

return [
    // Headers
    'invoice_title' => 'Rental Invoice',
    'initial_invoice_title' => 'INITIAL INVOICE',
    'final_invoice_title' => 'FINAL INVOICE',
    'invoice_number' => 'Invoice No.',
    'rental_number' => 'Rental No.',
    'inspection_date' => 'Inspection date',
    'invoice_date' => 'Invoice date',
    'due_date' => 'Due date',
    'status_paid' => 'Paid',
    'status_pending' => 'Pending',
    'status_partially_paid' => 'Partially paid',
    'status_failed' => 'Failed',
    
    // Client information
    'bill_to' => 'Bill to',
    'client_info' => 'Client information',
    'phone' => 'Phone',
    'email' => 'Email',
    'not_provided' => 'Not provided',
    
    // Rental information
    'rental_period' => 'Rental Period',
    'rental_start_date' => 'Start date',
    'rental_end_date' => 'End date',
    'rental_duration' => 'Duration',
    'daily_tariff' => 'Daily rate',
    'rental_details' => 'Rental details',
    'status' => 'Status',
    'payment_method' => 'Payment method',
    'payment_reference' => 'Payment reference',
    'confirmation_date' => 'Confirmation date',
    'start_date' => 'Actual start date',
    'end_date' => 'Actual end date',
    'day_singular' => 'day',
    'days_plural' => 'days',
    'per_day' => '/day',
    'initial_invoice_note' => 'This invoice will be updated after inspection of the returned equipment.',
    'final_invoice_note' => 'Final invoice after inspection of the returned equipment.',
    'deposit_fully_released' => 'The deposit of :amount € has been fully released.',
    'deposit_partially_released' => ':refund € of the deposit has been released. :retained € has been retained for fees.',
    'deposit_fully_retained' => 'The deposit of :amount € has been fully retained to cover fees.',
    'deposit_processing' => 'The deposit of :amount € is being processed.',
    'note' => 'Note',
    'deposit_colon' => 'Deposit',
    'ref_label' => 'Ref',
    
    // Product table
    'product' => 'Product',
    'quantity' => 'Qty',
    'unit_price' => 'Unit price',
    'daily_rate' => 'Daily rate',
    'days' => 'Days',
    'deposit' => 'Deposit',
    'total' => 'Total',
    'reference' => 'Ref',
    
    // Totals
    'subtotal_ht' => 'Subtotal excl. VAT',
    'subtotal_rental_ht' => 'Rental subtotal excl. VAT',
    'shipping_cost' => 'Shipping cost',
    'discount' => 'Discount',
    'total_deposit' => 'Total deposit',
    'vat' => 'VAT',
    'late_penalties' => 'Late penalties',
    'damage_fees' => 'Damage fees',
    'total_ttc' => 'TOTAL incl. VAT',
    'deposit_released' => 'Deposit released',
    
    // Statuses
    'status_pending' => 'Pending',
    'status_confirmed' => 'Confirmed',
    'status_active' => 'Active',
    'status_completed' => 'Completed',
    'status_cancelled' => 'Cancelled',
    'status_inspecting' => 'Under inspection',
    'status_finished' => 'Finished',
    
    // Payment methods
    'payment_card' => 'Credit card',
    'payment_transfer' => 'Bank transfer',
    'payment_cash' => 'Cash',
    'payment_stripe' => 'Credit card',
    
    // Payment messages
    'payment_completed' => 'Payment completed',
    'payment_completed_message' => 'Payment for this invoice was completed on :date by :name via :method.',
    'payment_completed_rental_message' => 'Payment for this rental invoice was completed on :date by :name via :method.',
    'payment_pending' => 'Payment pending',
    'payment_pending_message' => 'This invoice is awaiting payment. Please proceed with payment as soon as possible.',
    'payment_pending_rental_message' => 'This rental invoice is awaiting payment. Please proceed with payment as soon as possible.',
    
    // Inspection
    'inspection_notes' => 'Inspection notes',
    'inspection_performed' => 'Inspection performed on',
    'at' => 'at',
    
    // Conditions
    'sale_conditions' => 'Terms of sale',
    'sale_conditions_text' => 'Food products are not exchangeable or refundable for hygiene and food safety reasons. Non-food products can be returned within 14 days of delivery, in their original packaging and in perfect condition. Return costs are borne by the customer except in case of product defect.',
    'rental_conditions' => 'Rental conditions',
    'rental_conditions_text' => 'Rented equipment must be returned in the condition it was delivered. Any damage or loss will be charged at replacement price. In case of late return, penalties of :penalty_rate €/day will apply. The pre-authorized deposit will be released within 7 business days after equipment inspection, or captured in case of damages.',
    
    // Company
    'company_title' => 'Agricultural equipment - Purchase and rental',
    'vat_number' => 'VAT No.',
    'phone_label' => 'Tel',
    'email_label' => 'Email',
    
    // Invoice header
    'invoice' => 'INVOICE',
    'order_number' => 'Order No.',
    'billing_address' => 'BILLING ADDRESS',
    'shipping_address' => 'SHIPPING ADDRESS',
    'customer_info' => 'Customer information',
    'client' => 'Client',
    'order_details' => 'Order details',
    'tracking_number' => 'Tracking number',
    'shipping_date' => 'Shipping date',
    'delivery_date' => 'Delivery date',
    'invoice_generated' => 'Invoice automatically generated on',
    
    // Days
    'day' => 'day',
    'days_plural' => 'days',
];
