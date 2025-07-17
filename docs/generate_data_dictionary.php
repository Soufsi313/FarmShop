<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

// Créer un nouveau spreadsheet
$spreadsheet = new Spreadsheet();

// Supprimer la feuille par défaut
$spreadsheet->removeSheetByIndex(0);

// Définition des tables avec leurs champs
$tables = [
    'users' => [
        'description' => 'Table principale des utilisateurs du système',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique de l\'utilisateur'],
            ['name' => 'name', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Nom complet de l\'utilisateur'],
            ['name' => 'email', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => 'UNI', 'default' => '', 'description' => 'Adresse email unique'],
            ['name' => 'email_verified_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de verification de l\'email'],
            ['name' => 'password', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Mot de passe crypte'],
            ['name' => 'phone', 'type' => 'VARCHAR', 'size' => '20', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Numero de telephone'],
            ['name' => 'address', 'type' => 'TEXT', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Adresse complete'],
            ['name' => 'is_active', 'type' => 'BOOLEAN', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '1', 'description' => 'Statut actif/inactif'],
            ['name' => 'remember_token', 'type' => 'VARCHAR', 'size' => '100', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Token de memorisation'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'roles' => [
        'description' => 'Table des roles utilisateurs (Spatie Permission)',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique du role'],
            ['name' => 'name', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Nom du role'],
            ['name' => 'guard_name', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Garde de securite'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'permissions' => [
        'description' => 'Table des permissions (Spatie Permission)',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique de la permission'],
            ['name' => 'name', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Nom de la permission'],
            ['name' => 'guard_name', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Garde de securite'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'categories' => [
        'description' => 'Table des categories de produits',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique de la categorie'],
            ['name' => 'name', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Nom de la categorie'],
            ['name' => 'slug', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => 'UNI', 'default' => '', 'description' => 'URL-friendly de la categorie'],
            ['name' => 'description', 'type' => 'TEXT', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Description de la categorie'],
            ['name' => 'image', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Chemin vers l\'image'],
            ['name' => 'is_active', 'type' => 'BOOLEAN', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '1', 'description' => 'Statut actif/inactif'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'products' => [
        'description' => 'Table principale des produits',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique du produit'],
            ['name' => 'user_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers l\'utilisateur proprietaire'],
            ['name' => 'category_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers la categorie'],
            ['name' => 'name', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Nom du produit'],
            ['name' => 'slug', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => 'UNI', 'default' => '', 'description' => 'URL-friendly du produit'],
            ['name' => 'description', 'type' => 'TEXT', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Description detaillee'],
            ['name' => 'price', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'NON', 'key' => '', 'default' => '0.00', 'description' => 'Prix de vente'],
            ['name' => 'rental_price_per_day', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Prix de location par jour'],
            ['name' => 'deposit_amount', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Montant de la caution'],
            ['name' => 'stock_quantity', 'type' => 'INT', 'size' => '11', 'null' => 'NON', 'key' => '', 'default' => '0', 'description' => 'Quantite en stock'],
            ['name' => 'is_for_sale', 'type' => 'BOOLEAN', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '1', 'description' => 'Disponible a la vente'],
            ['name' => 'is_for_rent', 'type' => 'BOOLEAN', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '0', 'description' => 'Disponible a la location'],
            ['name' => 'is_active', 'type' => 'BOOLEAN', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '1', 'description' => 'Statut actif/inactif'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'product_images' => [
        'description' => 'Table des images des produits',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique de l\'image'],
            ['name' => 'product_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers le produit'],
            ['name' => 'image_path', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Chemin vers l\'image'],
            ['name' => 'alt_text', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Texte alternatif'],
            ['name' => 'is_primary', 'type' => 'BOOLEAN', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '0', 'description' => 'Image principale'],
            ['name' => 'sort_order', 'type' => 'INT', 'size' => '11', 'null' => 'NON', 'key' => '', 'default' => '0', 'description' => 'Ordre d\'affichage'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'carts' => [
        'description' => 'Table des paniers d\'achat',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique du panier'],
            ['name' => 'user_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers l\'utilisateur'],
            ['name' => 'session_id', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'ID de session pour utilisateurs non connectes'],
            ['name' => 'total_amount', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'NON', 'key' => '', 'default' => '0.00', 'description' => 'Montant total du panier'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'cart_items' => [
        'description' => 'Table des articles dans les paniers',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique de l\'article panier'],
            ['name' => 'cart_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers le panier'],
            ['name' => 'product_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers le produit'],
            ['name' => 'quantity', 'type' => 'INT', 'size' => '11', 'null' => 'NON', 'key' => '', 'default' => '1', 'description' => 'Quantite commandee'],
            ['name' => 'unit_price', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'NON', 'key' => '', 'default' => '0.00', 'description' => 'Prix unitaire'],
            ['name' => 'total_price', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'NON', 'key' => '', 'default' => '0.00', 'description' => 'Prix total'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'cart_locations' => [
        'description' => 'Table des paniers de location',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique du panier location'],
            ['name' => 'user_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers l\'utilisateur'],
            ['name' => 'start_date', 'type' => 'DATE', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Date de debut souhaitee'],
            ['name' => 'end_date', 'type' => 'DATE', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Date de fin souhaitee'],
            ['name' => 'total_amount', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'NON', 'key' => '', 'default' => '0.00', 'description' => 'Montant total'],
            ['name' => 'total_deposit', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'NON', 'key' => '', 'default' => '0.00', 'description' => 'Caution totale'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'orders' => [
        'description' => 'Table des commandes',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique de la commande'],
            ['name' => 'user_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers l\'utilisateur'],
            ['name' => 'order_number', 'type' => 'VARCHAR', 'size' => '50', 'null' => 'NON', 'key' => 'UNI', 'default' => '', 'description' => 'Numero de commande unique'],
            ['name' => 'status', 'type' => 'ENUM', 'size' => 'pending,confirmed,shipped,delivered,cancelled', 'null' => 'NON', 'key' => '', 'default' => 'pending', 'description' => 'Statut de la commande'],
            ['name' => 'total_amount', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'NON', 'key' => '', 'default' => '0.00', 'description' => 'Montant total'],
            ['name' => 'shipping_address', 'type' => 'TEXT', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Adresse de livraison'],
            ['name' => 'payment_method', 'type' => 'VARCHAR', 'size' => '50', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Methode de paiement'],
            ['name' => 'payment_status', 'type' => 'ENUM', 'size' => 'pending,paid,failed,refunded', 'null' => 'NON', 'key' => '', 'default' => 'pending', 'description' => 'Statut du paiement'],
            ['name' => 'notes', 'type' => 'TEXT', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Notes complementaires'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'rentals' => [
        'description' => 'Table des locations actives',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique de la location'],
            ['name' => 'user_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers l\'utilisateur'],
            ['name' => 'rental_number', 'type' => 'VARCHAR', 'size' => '50', 'null' => 'NON', 'key' => 'UNI', 'default' => '', 'description' => 'Numero de location unique'],
            ['name' => 'status', 'type' => 'ENUM', 'size' => 'pending,active,completed,cancelled,overdue', 'null' => 'NON', 'key' => '', 'default' => 'pending', 'description' => 'Statut de la location'],
            ['name' => 'start_date', 'type' => 'DATE', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Date de debut de location'],
            ['name' => 'end_date', 'type' => 'DATE', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Date de fin prevue'],
            ['name' => 'actual_return_date', 'type' => 'DATE', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de retour effective'],
            ['name' => 'total_days', 'type' => 'INT', 'size' => '11', 'null' => 'NON', 'key' => '', 'default' => '0', 'description' => 'Nombre total de jours'],
            ['name' => 'daily_rate', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'NON', 'key' => '', 'default' => '0.00', 'description' => 'Tarif journalier'],
            ['name' => 'total_amount', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'NON', 'key' => '', 'default' => '0.00', 'description' => 'Montant total'],
            ['name' => 'deposit_amount', 'type' => 'DECIMAL', 'size' => '10,2', 'null' => 'NON', 'key' => '', 'default' => '0.00', 'description' => 'Montant de la caution'],
            ['name' => 'deposit_status', 'type' => 'ENUM', 'size' => 'pending,paid,refunded,withheld', 'null' => 'NON', 'key' => '', 'default' => 'pending', 'description' => 'Statut de la caution'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'contacts' => [
        'description' => 'Table des messages de contact',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique du contact'],
            ['name' => 'user_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'OUI', 'key' => 'FOR', 'default' => 'NULL', 'description' => 'Reference vers l\'utilisateur (optionnel)'],
            ['name' => 'name', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Nom du contact'],
            ['name' => 'email', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Email du contact'],
            ['name' => 'subject', 'type' => 'VARCHAR', 'size' => '255', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Sujet du message'],
            ['name' => 'message', 'type' => 'TEXT', 'size' => '', 'null' => 'NON', 'key' => '', 'default' => '', 'description' => 'Contenu du message'],
            ['name' => 'status', 'type' => 'ENUM', 'size' => 'new,read,replied,closed', 'null' => 'NON', 'key' => '', 'default' => 'new', 'description' => 'Statut du message'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'product_likes' => [
        'description' => 'Table des likes sur les produits',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique du like'],
            ['name' => 'user_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers l\'utilisateur'],
            ['name' => 'product_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers le produit'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ],
    'wishlists' => [
        'description' => 'Table des listes de souhaits',
        'fields' => [
            ['name' => 'id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'PRI', 'default' => 'AUTO_INCREMENT', 'description' => 'Identifiant unique de la wishlist'],
            ['name' => 'user_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers l\'utilisateur'],
            ['name' => 'product_id', 'type' => 'BIGINT UNSIGNED', 'size' => '20', 'null' => 'NON', 'key' => 'FOR', 'default' => '', 'description' => 'Reference vers le produit'],
            ['name' => 'created_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de creation'],
            ['name' => 'updated_at', 'type' => 'TIMESTAMP', 'size' => '', 'null' => 'OUI', 'key' => '', 'default' => 'NULL', 'description' => 'Date de derniere modification']
        ]
    ]
];

// Créer la feuille sommaire
$summarySheet = $spreadsheet->createSheet();
$summarySheet->setTitle('Sommaire');
$spreadsheet->setActiveSheetIndex(0);

// En-tête du sommaire
$summarySheet->setCellValue('A1', 'DICTIONNAIRE DE DONNEES - FARMSHOP');
$summarySheet->setCellValue('A2', 'SOMMAIRE DES TABLES');
$summarySheet->setCellValue('A4', 'Table');
$summarySheet->setCellValue('B4', 'Description');
$summarySheet->setCellValue('C4', 'Nombre de champs');
$summarySheet->setCellValue('D4', 'Feuille');

// Style pour l'en-tête
$summarySheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(16);
$summarySheet->getStyle('A2:D2')->getFont()->setBold(true)->setSize(14);
$summarySheet->getStyle('A4:D4')->getFont()->setBold(true);
$summarySheet->getStyle('A4:D4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E3F2FD');

// Remplir le sommaire
$row = 5;
foreach ($tables as $tableName => $tableData) {
    $summarySheet->setCellValue("A{$row}", $tableName);
    $summarySheet->setCellValue("B{$row}", $tableData['description']);
    $summarySheet->setCellValue("C{$row}", count($tableData['fields']));
    $summarySheet->setCellValue("D{$row}", $tableName);
    $row++;
}

// Ajuster les largeurs des colonnes
$summarySheet->getColumnDimension('A')->setWidth(20);
$summarySheet->getColumnDimension('B')->setWidth(50);
$summarySheet->getColumnDimension('C')->setWidth(15);
$summarySheet->getColumnDimension('D')->setWidth(15);

// Créer une feuille pour chaque table
foreach ($tables as $tableName => $tableData) {
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle($tableName);
    
    // En-tête de la feuille
    $sheet->setCellValue('A1', "TABLE: " . strtoupper($tableName));
    $sheet->setCellValue('A2', $tableData['description']);
    
    // En-têtes des colonnes
    $sheet->setCellValue('A4', 'Champ');
    $sheet->setCellValue('B4', 'Type');
    $sheet->setCellValue('C4', 'Taille');
    $sheet->setCellValue('D4', 'NULL');
    $sheet->setCellValue('E4', 'Cle');
    $sheet->setCellValue('F4', 'Defaut');
    $sheet->setCellValue('G4', 'Description');
    $sheet->setCellValue('H4', 'Regles de validation');
    $sheet->setCellValue('I4', 'Contraintes');
    
    // Style pour l'en-tête
    $sheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A2:I2')->getFont()->setItalic(true)->setSize(12);
    $sheet->getStyle('A4:I4')->getFont()->setBold(true);
    $sheet->getStyle('A4:I4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E8F5E8');
    
    // Bordures pour l'en-tête
    $sheet->getStyle('A4:I4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    
    // Remplir les données
    $row = 5;
    foreach ($tableData['fields'] as $field) {
        $sheet->setCellValue("A{$row}", $field['name']);
        $sheet->setCellValue("B{$row}", $field['type']);
        $sheet->setCellValue("C{$row}", $field['size']);
        $sheet->setCellValue("D{$row}", $field['null']);
        $sheet->setCellValue("E{$row}", $field['key']);
        $sheet->setCellValue("F{$row}", $field['default']);
        $sheet->setCellValue("G{$row}", $field['description']);
        
        // Règles de validation spécifiques
        $validation = '';
        switch ($field['type']) {
            case 'VARCHAR':
                $validation = "Chaine de caracteres max {$field['size']}";
                break;
            case 'DECIMAL':
                $validation = "Nombre decimal format {$field['size']}";
                break;
            case 'INT':
            case 'BIGINT UNSIGNED':
                $validation = "Nombre entier";
                break;
            case 'BOOLEAN':
                $validation = "Booleen (0 ou 1)";
                break;
            case 'ENUM':
                $validation = "Valeurs: {$field['size']}";
                break;
            case 'DATE':
                $validation = "Format date YYYY-MM-DD";
                break;
            case 'TIMESTAMP':
                $validation = "Format datetime YYYY-MM-DD HH:MM:SS";
                break;
            default:
                $validation = "Selon type {$field['type']}";
        }
        $sheet->setCellValue("H{$row}", $validation);
        
        // Contraintes
        $constraints = [];
        if ($field['key'] == 'PRI') $constraints[] = 'Cle primaire';
        if ($field['key'] == 'FOR') $constraints[] = 'Cle etrangere';
        if ($field['key'] == 'UNI') $constraints[] = 'Unique';
        if ($field['null'] == 'NON') $constraints[] = 'Obligatoire';
        
        $sheet->setCellValue("I{$row}", implode(', ', $constraints));
        
        // Bordures pour les données
        $sheet->getStyle("A{$row}:I{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        $row++;
    }
    
    // Ajuster les largeurs des colonnes
    $sheet->getColumnDimension('A')->setWidth(20);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(10);
    $sheet->getColumnDimension('D')->setWidth(8);
    $sheet->getColumnDimension('E')->setWidth(8);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(30);
    $sheet->getColumnDimension('H')->setWidth(25);
    $sheet->getColumnDimension('I')->setWidth(20);
}

// Sauvegarder le fichier
$writer = new Xlsx($spreadsheet);
$writer->save(__DIR__ . '/09_Dictionnaire_Donnees_Complet.xlsx');

echo "Dictionnaire de donnees Excel cree avec succes !\n";
echo "Emplacement : " . __DIR__ . "/09_Dictionnaire_Donnees_Complet.xlsx\n";
echo "Nombre de tables documentees : " . count($tables) . "\n";
echo "Feuilles creees : Sommaire + " . count($tables) . " tables\n";
echo "\nContenu du fichier Excel :\n";
echo "- Feuille 1: Sommaire de toutes les tables\n";
foreach ($tables as $tableName => $tableData) {
    echo "- Feuille {$tableName}: " . count($tableData['fields']) . " champs documentes\n";
}

?>
