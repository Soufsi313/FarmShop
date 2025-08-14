<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     description="Modèle de produit",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique du produit"),
 *     @OA\Property(property="name", type="string", example="Bêche professionnelle", description="Nom du produit"),
 *     @OA\Property(property="slug", type="string", example="beche-professionnelle", description="Slug URL du produit"),
 *     @OA\Property(property="description", type="string", example="Bêche robuste pour jardinage professionnel", description="Description du produit"),
 *     @OA\Property(property="short_description", type="string", example="Bêche robuste", description="Description courte"),
 *     @OA\Property(property="price", type="number", format="float", example=29.99, description="Prix de vente"),
 *     @OA\Property(property="rental_price_per_day", type="number", format="float", example=5.00, description="Prix de location par jour"),
 *     @OA\Property(property="deposit_amount", type="number", format="float", example=25.00, description="Montant de la caution"),
 *     @OA\Property(property="type", type="string", enum={"sale", "rental", "both"}, example="both", description="Type de produit"),
 *     @OA\Property(property="quantity", type="integer", example=50, description="Quantité en stock"),
 *     @OA\Property(property="rental_stock", type="integer", example=10, description="Stock disponible pour location"),
 *     @OA\Property(property="is_active", type="boolean", example=true, description="Produit actif"),
 *     @OA\Property(property="is_rental_available", type="boolean", example=true, description="Disponible en location"),
 *     @OA\Property(property="main_image", type="string", example="products/beche.jpg", description="Image principale"),
 *     @OA\Property(property="images", type="array", @OA\Items(type="string"), description="Images additionnelles"),
 *     @OA\Property(property="category_id", type="integer", example=1, description="ID de la catégorie"),
 *     @OA\Property(property="rental_category_id", type="integer", example=1, description="ID de la catégorie de location"),
 *     @OA\Property(property="min_rental_days", type="integer", example=1, description="Durée minimale de location"),
 *     @OA\Property(property="max_rental_days", type="integer", example=30, description="Durée maximale de location"),
 *     @OA\Property(property="low_stock_threshold", type="integer", example=5, description="Seuil de stock bas"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification"),
 *     @OA\Property(
 *         property="category",
 *         ref="#/components/schemas/Category",
 *         description="Catégorie du produit"
 *     ),
 *     @OA\Property(
 *         property="rental_category",
 *         ref="#/components/schemas/RentalCategory",
 *         description="Catégorie de location"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Category",
 *     type="object",
 *     title="Category",
 *     description="Modèle de catégorie",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="name", type="string", example="Outils de jardinage", description="Nom de la catégorie"),
 *     @OA\Property(property="slug", type="string", example="outils-jardinage", description="Slug URL"),
 *     @OA\Property(property="description", type="string", example="Tous les outils pour le jardinage", description="Description"),
 *     @OA\Property(property="image", type="string", example="categories/outils.jpg", description="Image de la catégorie"),
 *     @OA\Property(property="is_active", type="boolean", example=true, description="Catégorie active"),
 *     @OA\Property(property="sort_order", type="integer", example=1, description="Ordre d'affichage"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification")
 * )
 *
 * @OA\Schema(
 *     schema="RentalCategory",
 *     type="object",
 *     title="RentalCategory",
 *     description="Modèle de catégorie de location",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="name", type="string", example="Équipement agricole", description="Nom de la catégorie"),
 *     @OA\Property(property="slug", type="string", example="equipement-agricole", description="Slug URL"),
 *     @OA\Property(property="description", type="string", example="Matériel agricole professionnel", description="Description"),
 *     @OA\Property(property="image", type="string", example="rental-categories/agricole.jpg", description="Image"),
 *     @OA\Property(property="is_active", type="boolean", example=true, description="Catégorie active"),
 *     @OA\Property(property="sort_order", type="integer", example=1, description="Ordre d'affichage"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification")
 * )
 *
 * @OA\Schema(
 *     schema="CartItem",
 *     type="object",
 *     title="CartItem",
 *     description="Élément du panier",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="cart_id", type="integer", example=1, description="ID du panier"),
 *     @OA\Property(property="product_id", type="integer", example=1, description="ID du produit"),
 *     @OA\Property(property="quantity", type="integer", example=2, description="Quantité"),
 *     @OA\Property(property="unit_price", type="number", format="float", example=29.99, description="Prix unitaire"),
 *     @OA\Property(property="total_price", type="number", format="float", example=59.98, description="Prix total"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date d'ajout"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification"),
 *     @OA\Property(
 *         property="product",
 *         ref="#/components/schemas/Product",
 *         description="Produit associé"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="Modèle d'utilisateur",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="name", type="string", example="Jean Dupont", description="Nom complet"),
 *     @OA\Property(property="email", type="string", format="email", example="jean@example.com", description="Adresse e-mail"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", description="Date de vérification email"),
 *     @OA\Property(property="role", type="string", enum={"user", "admin"}, example="user", description="Rôle utilisateur"),
 *     @OA\Property(property="phone", type="string", example="+33123456789", description="Numéro de téléphone"),
 *     @OA\Property(property="address", type="string", example="123 Rue de la Paix", description="Adresse"),
 *     @OA\Property(property="city", type="string", example="Paris", description="Ville"),
 *     @OA\Property(property="postal_code", type="string", example="75001", description="Code postal"),
 *     @OA\Property(property="country", type="string", example="France", description="Pays"),
 *     @OA\Property(property="newsletter_subscribed", type="boolean", example=true, description="Abonné à la newsletter"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification")
 * )
 *
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     title="Order",
 *     description="Modèle de commande d'achat",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="order_number", type="string", example="ORD-20240814-001", description="Numéro de commande"),
 *     @OA\Property(property="user_id", type="integer", example=1, description="ID de l'utilisateur"),
 *     @OA\Property(property="status", type="string", enum={"pending", "confirmed", "processing", "shipped", "delivered", "cancelled"}, example="confirmed", description="Statut de la commande"),
 *     @OA\Property(property="payment_status", type="string", enum={"pending", "paid", "failed", "refunded"}, example="paid", description="Statut du paiement"),
 *     @OA\Property(property="payment_method", type="string", example="stripe", description="Méthode de paiement"),
 *     @OA\Property(property="subtotal", type="number", format="float", example=99.99, description="Sous-total"),
 *     @OA\Property(property="tax_amount", type="number", format="float", example=20.00, description="Montant TVA"),
 *     @OA\Property(property="shipping_amount", type="number", format="float", example=5.99, description="Frais de port"),
 *     @OA\Property(property="total_amount", type="number", format="float", example=125.98, description="Montant total"),
 *     @OA\Property(property="billing_address", type="object", description="Adresse de facturation"),
 *     @OA\Property(property="shipping_address", type="object", description="Adresse de livraison"),
 *     @OA\Property(property="notes", type="string", description="Notes de commande"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification")
 * )
 *
 * @OA\Schema(
 *     schema="OrderLocation",
 *     type="object",
 *     title="OrderLocation",
 *     description="Modèle de commande de location",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="order_number", type="string", example="LOC-20240814-001", description="Numéro de location"),
 *     @OA\Property(property="user_id", type="integer", example=1, description="ID de l'utilisateur"),
 *     @OA\Property(property="product_id", type="integer", example=1, description="ID du produit loué"),
 *     @OA\Property(property="status", type="string", enum={"pending", "confirmed", "active", "completed", "closed", "inspecting", "finished", "cancelled"}, example="active", description="Statut de la location"),
 *     @OA\Property(property="start_date", type="string", format="date", example="2024-08-15", description="Date de début"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2024-08-20", description="Date de fin"),
 *     @OA\Property(property="rental_days", type="number", format="float", example=5, description="Nombre de jours"),
 *     @OA\Property(property="daily_rate", type="number", format="float", example=10.00, description="Tarif journalier"),
 *     @OA\Property(property="total_rental_cost", type="number", format="float", example=50.00, description="Coût total location"),
 *     @OA\Property(property="deposit_amount", type="number", format="float", example=100.00, description="Montant caution"),
 *     @OA\Property(property="deposit_status", type="string", enum={"pending", "authorized", "captured", "released"}, example="authorized", description="Statut caution"),
 *     @OA\Property(property="late_fees", type="number", format="float", example=0, description="Frais de retard"),
 *     @OA\Property(property="damage_cost", type="number", format="float", example=0, description="Coût des dégâts"),
 *     @OA\Property(property="inspection_status", type="string", enum={"pending", "completed"}, example="pending", description="Statut inspection")
 * )
 *
 * @OA\Schema(
 *     schema="ApiResponse",
 *     type="object",
 *     title="ApiResponse",
 *     description="Réponse API standard",
 *     @OA\Property(property="success", type="boolean", example=true, description="Succès de l'opération"),
 *     @OA\Property(property="message", type="string", example="Opération réussie", description="Message de réponse"),
 *     @OA\Property(property="data", type="object", description="Données de réponse"),
 *     @OA\Property(property="errors", type="object", description="Erreurs de validation")
 * )
 *
 * @OA\Schema(
 *     schema="PaginatedResponse",
 *     type="object",
 *     title="PaginatedResponse",
 *     description="Réponse paginée",
 *     @OA\Property(property="current_page", type="integer", example=1),
 *     @OA\Property(property="data", type="array", @OA\Items(type="object")),
 *     @OA\Property(property="first_page_url", type="string"),
 *     @OA\Property(property="from", type="integer"),
 *     @OA\Property(property="last_page", type="integer"),
 *     @OA\Property(property="last_page_url", type="string"),
 *     @OA\Property(property="links", type="array", @OA\Items(type="object")),
 *     @OA\Property(property="next_page_url", type="string"),
 *     @OA\Property(property="path", type="string"),
 *     @OA\Property(property="per_page", type="integer"),
 *     @OA\Property(property="prev_page_url", type="string"),
 *     @OA\Property(property="to", type="integer"),
 *     @OA\Property(property="total", type="integer")
 * )
 *
 * @OA\Schema(
 *     schema="Cart",
 *     type="object",
 *     title="Cart",
 *     description="Panier d'achat",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="status", type="string", enum={"active", "converted", "abandoned"}, example="active"),
 *     @OA\Property(property="total_amount", type="number", format="float", example=125.50),
 *     @OA\Property(property="items_count", type="integer", example=3),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/CartItem")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="OrderItem",
 *     type="object",
 *     title="OrderItem",
 *     description="Article de commande",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="order_id", type="integer", example=1),
 *     @OA\Property(property="product_id", type="integer", example=1),
 *     @OA\Property(property="quantity", type="integer", example=2),
 *     @OA\Property(property="unit_price", type="number", format="float", example=29.99),
 *     @OA\Property(property="total_price", type="number", format="float", example=59.98),
 *     @OA\Property(property="product_snapshot", type="object"),
 *     @OA\Property(property="product", ref="#/components/schemas/Product")
 * )
 *
 * @OA\Schema(
 *     schema="SpecialOffer",
 *     type="object",
 *     title="SpecialOffer",
 *     description="Offre spéciale",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Promotion Printemps"),
 *     @OA\Property(property="description", type="string", example="20% de réduction sur tous les outils"),
 *     @OA\Property(property="discount_type", type="string", enum={"percentage", "fixed"}, example="percentage"),
 *     @OA\Property(property="discount_value", type="number", format="float", example=20.00),
 *     @OA\Property(property="min_amount", type="number", format="float", nullable=true, example=50.00),
 *     @OA\Property(property="start_date", type="string", format="date-time"),
 *     @OA\Property(property="end_date", type="string", format="date-time"),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="usage_limit", type="integer", nullable=true, example=100),
 *     @OA\Property(property="usage_count", type="integer", example=15),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="Wishlist",
 *     type="object",
 *     title="Wishlist",
 *     description="Liste de souhaits",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="product_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="product", ref="#/components/schemas/Product"),
 *     @OA\Property(property="user", ref="#/components/schemas/User")
 * )
 *
 * @OA\Schema(
 *     schema="ValidationError",
 *     type="object",
 *     title="ValidationError",
 *     description="Erreur de validation",
 *     @OA\Property(property="success", type="boolean", example=false),
 *     @OA\Property(property="message", type="string", example="Les données fournies sont invalides"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         description="Détails des erreurs de validation",
 *         @OA\AdditionalProperties(
 *             type="array",
 *             @OA\Items(type="string")
 *         ),
 *         example={
 *             "email": {"L'adresse e-mail est requise"},
 *             "password": {"Le mot de passe doit contenir au moins 8 caractères"}
 *         }
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="BlogPost",
 *     type="object",
 *     title="BlogPost",
 *     description="Article de blog",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="title", type="string", example="Guide du jardinage bio", description="Titre de l'article"),
 *     @OA\Property(property="slug", type="string", example="guide-jardinage-bio", description="Slug URL"),
 *     @OA\Property(property="excerpt", type="string", example="Découvrez les secrets du jardinage biologique...", description="Extrait de l'article"),
 *     @OA\Property(property="content", type="string", example="Contenu complet de l'article...", description="Contenu principal"),
 *     @OA\Property(property="featured_image", type="string", example="blog/jardinage-bio.jpg", description="Image principale"),
 *     @OA\Property(property="status", type="string", enum={"draft", "published", "scheduled", "archived"}, example="published", description="Statut de publication"),
 *     @OA\Property(property="author_id", type="integer", example=1, description="ID de l'auteur"),
 *     @OA\Property(property="category_id", type="integer", example=1, description="ID de la catégorie"),
 *     @OA\Property(property="views_count", type="integer", example=1250, description="Nombre de vues"),
 *     @OA\Property(property="likes_count", type="integer", example=45, description="Nombre de likes"),
 *     @OA\Property(property="comments_count", type="integer", example=12, description="Nombre de commentaires"),
 *     @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"bio", "jardinage", "écologie"}, description="Tags de l'article"),
 *     @OA\Property(property="meta_title", type="string", example="Guide complet du jardinage bio", description="Titre SEO"),
 *     @OA\Property(property="meta_description", type="string", example="Apprenez les techniques du jardinage biologique...", description="Description SEO"),
 *     @OA\Property(property="published_at", type="string", format="date-time", description="Date de publication"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification"),
 *     @OA\Property(property="author", ref="#/components/schemas/User", description="Auteur de l'article"),
 *     @OA\Property(property="category", ref="#/components/schemas/BlogCategory", description="Catégorie de l'article")
 * )
 *
 * @OA\Schema(
 *     schema="BlogCategory",
 *     type="object",
 *     title="BlogCategory",
 *     description="Catégorie de blog",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="name", type="string", example="Jardinage", description="Nom de la catégorie"),
 *     @OA\Property(property="slug", type="string", example="jardinage", description="Slug URL"),
 *     @OA\Property(property="description", type="string", example="Articles sur le jardinage et l'horticulture", description="Description"),
 *     @OA\Property(property="image", type="string", example="categories/jardinage.jpg", description="Image de la catégorie"),
 *     @OA\Property(property="color", type="string", example="#4CAF50", description="Couleur associée"),
 *     @OA\Property(property="is_active", type="boolean", example=true, description="Catégorie active"),
 *     @OA\Property(property="sort_order", type="integer", example=1, description="Ordre d'affichage"),
 *     @OA\Property(property="posts_count", type="integer", example=25, description="Nombre d'articles"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification")
 * )
 *
 * @OA\Schema(
 *     schema="BlogComment",
 *     type="object",
 *     title="BlogComment",
 *     description="Commentaire de blog",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="post_id", type="integer", example=1, description="ID de l'article"),
 *     @OA\Property(property="user_id", type="integer", example=1, description="ID de l'utilisateur"),
 *     @OA\Property(property="parent_id", type="integer", nullable=true, example=null, description="ID du commentaire parent"),
 *     @OA\Property(property="content", type="string", example="Excellent article, très informatif !", description="Contenu du commentaire"),
 *     @OA\Property(property="status", type="string", enum={"pending", "approved", "rejected", "spam"}, example="approved", description="Statut de modération"),
 *     @OA\Property(property="is_pinned", type="boolean", example=false, description="Commentaire épinglé"),
 *     @OA\Property(property="likes_count", type="integer", example=5, description="Nombre de likes"),
 *     @OA\Property(property="replies_count", type="integer", example=2, description="Nombre de réponses"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification"),
 *     @OA\Property(property="user", ref="#/components/schemas/User", description="Auteur du commentaire"),
 *     @OA\Property(property="post", ref="#/components/schemas/BlogPost", description="Article associé"),
 *     @OA\Property(
 *         property="replies",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/BlogComment"),
 *         description="Réponses au commentaire"
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="ProductLike",
 *     type="object",
 *     title="ProductLike",
 *     description="Like sur un produit",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="product_id", type="integer", example=1),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="product", ref="#/components/schemas/Product"),
 *     @OA\Property(property="user", ref="#/components/schemas/User")
 * )
 *
 * @OA\Schema(
 *     schema="BlogCommentReport",
 *     type="object",
 *     title="BlogCommentReport",
 *     description="Signalement de commentaire de blog",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="comment_id", type="integer", example=1, description="ID du commentaire signalé"),
 *     @OA\Property(property="reporter_id", type="integer", example=5, description="ID de l'utilisateur signalant"),
 *     @OA\Property(property="reviewer_id", type="integer", nullable=true, example=2, description="ID de l'admin revieweur"),
 *     @OA\Property(property="reason", type="string", enum={"spam", "inappropriate", "harassment", "hate_speech", "violence", "other"}, example="spam", description="Raison du signalement"),
 *     @OA\Property(property="description", type="string", example="Ce commentaire contient du spam publicitaire", description="Description détaillée"),
 *     @OA\Property(property="status", type="string", enum={"pending", "reviewing", "resolved", "dismissed"}, example="pending", description="Statut du signalement"),
 *     @OA\Property(property="priority", type="string", enum={"low", "medium", "high", "urgent"}, example="medium", description="Priorité du signalement"),
 *     @OA\Property(property="admin_notes", type="string", nullable=true, example="Signalement traité, commentaire supprimé", description="Notes de l'administrateur"),
 *     @OA\Property(property="resolution", type="string", nullable=true, example="comment_deleted", description="Résolution appliquée"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification"),
 *     @OA\Property(property="reviewed_at", type="string", format="date-time", nullable=true, description="Date de review"),
 *     @OA\Property(property="resolved_at", type="string", format="date-time", nullable=true, description="Date de résolution"),
 *     @OA\Property(property="comment", ref="#/components/schemas/BlogComment", description="Commentaire signalé"),
 *     @OA\Property(property="reporter", ref="#/components/schemas/User", description="Utilisateur signalant"),
 *     @OA\Property(property="reviewer", ref="#/components/schemas/User", nullable=true, description="Administrateur revieweur")
 * )
 *
 * @OA\Schema(
 *     schema="OrderReturn",
 *     type="object",
 *     title="OrderReturn",
 *     description="Modèle de demande de retour avec système d'inspection",
 *     @OA\Property(property="id", type="integer", example=1, description="Identifiant unique"),
 *     @OA\Property(property="order_id", type="integer", example=123, description="ID de la commande"),
 *     @OA\Property(property="order_item_id", type="integer", example=456, description="ID de l'article commandé"),
 *     @OA\Property(property="user_id", type="integer", example=5, description="ID de l'utilisateur"),
 *     @OA\Property(property="return_number", type="string", example="RET-2024-001", description="Numéro de retour unique"),
 *     @OA\Property(property="quantity_returned", type="integer", example=2, description="Quantité retournée"),
 *     @OA\Property(property="reason", type="string", enum={"defective", "damaged", "wrong_item", "not_satisfied", "size_issue", "other"}, example="defective", description="Raison du retour"),
 *     @OA\Property(property="description", type="string", example="Le produit présente un défaut de fabrication", description="Description détaillée"),
 *     @OA\Property(property="images", type="array", @OA\Items(type="string"), example={"return_photos/item1.jpg", "return_photos/item2.jpg"}, description="Photos du produit retourné"),
 *     @OA\Property(property="status", type="string", enum={"pending", "approved", "rejected", "returned", "received", "inspecting", "refunded", "cancelled"}, example="approved", description="Statut de la demande"),
 *     @OA\Property(property="status_history", type="array", @OA\Items(type="object"), description="Historique des changements de statut"),
 *     @OA\Property(property="status_updated_at", type="string", format="date-time", description="Date du dernier changement de statut"),
 *     @OA\Property(property="requested_at", type="string", format="date-time", description="Date de demande"),
 *     @OA\Property(property="approved_at", type="string", format="date-time", nullable=true, description="Date d'approbation"),
 *     @OA\Property(property="item_received_at", type="string", format="date-time", nullable=true, description="Date de réception du produit"),
 *     @OA\Property(property="inspected_at", type="string", format="date-time", nullable=true, description="Date d'inspection"),
 *     @OA\Property(property="refunded_at", type="string", format="date-time", nullable=true, description="Date de remboursement"),
 *     @OA\Property(property="refund_amount", type="number", format="float", example=149.99, description="Montant du remboursement"),
 *     @OA\Property(property="refund_method", type="string", enum={"original_payment", "bank_transfer", "store_credit"}, nullable=true, example="original_payment", description="Méthode de remboursement"),
 *     @OA\Property(property="refund_transaction_id", type="string", nullable=true, example="TXN-456789", description="ID de transaction de remboursement"),
 *     @OA\Property(property="refund_processed", type="boolean", example=true, description="Remboursement traité"),
 *     @OA\Property(property="return_shipping_address", type="string", nullable=true, description="Adresse de retour"),
 *     @OA\Property(property="return_tracking_number", type="string", nullable=true, example="TRACK-789123", description="Numéro de suivi"),
 *     @OA\Property(property="return_shipping_cost", type="number", format="float", nullable=true, example=12.50, description="Coût d'expédition"),
 *     @OA\Property(property="inspection_result", type="string", enum={"excellent", "good", "fair", "poor"}, nullable=true, example="good", description="Résultat de l'inspection"),
 *     @OA\Property(property="inspection_notes", type="string", nullable=true, example="Produit reçu en bon état", description="Notes d'inspection"),
 *     @OA\Property(property="inspection_images", type="array", @OA\Items(type="string"), nullable=true, description="Photos d'inspection"),
 *     @OA\Property(property="inspected_by", type="integer", nullable=true, example=2, description="ID de l'inspecteur"),
 *     @OA\Property(property="approved_by", type="integer", nullable=true, example=2, description="ID de l'admin qui a approuvé"),
 *     @OA\Property(property="admin_notes", type="string", nullable=true, example="Retour approuvé après vérification", description="Notes de l'administrateur"),
 *     @OA\Property(property="rejection_reason", type="string", enum={"condition_not_met", "out_of_deadline", "insufficient_evidence", "policy_violation"}, nullable=true, example="out_of_deadline", description="Raison du rejet"),
 *     @OA\Property(property="email_notifications_sent", type="array", @OA\Items(type="string"), description="Notifications envoyées"),
 *     @OA\Property(property="last_notification_sent_at", type="string", format="date-time", nullable=true, description="Dernière notification"),
 *     @OA\Property(property="metadata", type="object", nullable=true, description="Métadonnées additionnelles"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Date de création"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Date de modification"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, description="Date de suppression"),
 *     @OA\Property(property="user", ref="#/components/schemas/User", description="Utilisateur demandeur"),
 *     @OA\Property(property="order", ref="#/components/schemas/Order", description="Commande associée"),
 *     @OA\Property(property="orderItem", ref="#/components/schemas/OrderItem", description="Article de commande")
 * )
 */
class SwaggerSchemas
{
    // Ce fichier contient uniquement les schémas pour la documentation Swagger
}
