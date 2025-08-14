<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *     title="FarmShop API",
 *     version="1.0.0",
 *     description="API complète pour la plateforme e-commerce FarmShop. Cette API permet de gérer les produits, commandes, locations, paiements et bien plus encore.",
 *     termsOfService="https://farmshop.local/terms",
 *     @OA\Contact(
 *         email="contact@farmshop.local",
 *         name="Support API FarmShop",
 *         url="https://farmshop.local/support"
 *     ),
 *     @OA\License(
 *         name="MIT License",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Serveur de développement FarmShop API"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Serveur de développement alternatif"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Authentification via token Sanctum (Bearer Token)"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="session",
 *     type="apiKey",
 *     in="cookie",
 *     name="laravel_session",
 *     description="Authentification via session Laravel (pour interface web)"
 * )
 *
 * @OA\Tag(
 *     name="Authentication",
 *     description="Gestion de l'authentification et des utilisateurs"
 * )
 *
 * @OA\Tag(
 *     name="Products",
 *     description="Gestion des produits et catalogues"
 * )
 *
 * @OA\Tag(
 *     name="Categories",
 *     description="Gestion des catégories de produits"
 * )
 *
 * @OA\Tag(
 *     name="Rental Categories",
 *     description="Gestion des catégories de location"
 * )
 *
 * @OA\Tag(
 *     name="Cart",
 *     description="Gestion du panier d'achat"
 * )
 *
 * @OA\Tag(
 *     name="Cart Location",
 *     description="Gestion du panier de location"
 * )
 *
 * @OA\Tag(
 *     name="Orders",
 *     description="Gestion des commandes d'achat"
 * )
 *
 * @OA\Tag(
 *     name="Rental Orders",
 *     description="Gestion des commandes de location"
 * )
 *
 * @OA\Tag(
 *     name="Returns",
 *     description="Gestion des retours et remboursements"
 * )
 *
 * @OA\Tag(
 *     name="Payments",
 *     description="Gestion des paiements et transactions"
 * )
 *
 * @OA\Tag(
 *     name="Users",
 *     description="Gestion des utilisateurs et profils"
 * )
 *
 * @OA\Tag(
 *     name="Newsletter",
 *     description="Système de newsletter et abonnements"
 * )
 *
 * @OA\Tag(
 *     name="Blog",
 *     description="Gestion du blog et articles"
 * )
 *
 * @OA\Tag(
 *     name="Messages",
 *     description="Système de messagerie et notifications"
 * )
 *
 * @OA\Tag(
 *     name="Special Offers",
 *     description="Gestion des offres spéciales et promotions"
 * )
 *
 * @OA\Tag(
 *     name="Wishlist",
 *     description="Gestion de la liste de souhaits"
 * )
 *
 * @OA\Tag(
 *     name="Likes",
 *     description="Système de likes sur les produits"
 * )
 *
 * @OA\Tag(
 *     name="Cookies",
 *     description="Gestion des préférences cookies et RGPD"
 * )
 *
 * @OA\Tag(
 *     name="Rental Constraints",
 *     description="Contraintes et disponibilités pour les locations"
 * )
 *
 * @OA\Tag(
 *     name="Admin",
 *     description="Interface d'administration - accès restreint"
 * )
 */
class BaseApiController extends Controller
{
    //
}
