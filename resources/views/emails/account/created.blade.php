<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur FarmShop</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #16a34a 0%, #ea580c 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.95;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #fef3c7 100%);
            border-left: 4px solid #16a34a;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .welcome-box h2 {
            margin: 0 0 10px 0;
            color: #15803d;
            font-size: 20px;
        }
        .welcome-box p {
            margin: 0;
            color: #374151;
        }
        .account-details {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        .account-details h3 {
            margin: 0 0 15px 0;
            color: #16a34a;
            font-size: 18px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .label {
            font-weight: 600;
            color: #374151;
        }
        .value {
            color: #6b7280;
            text-align: right;
        }
        .features {
            margin: 30px 0;
        }
        .features h3 {
            color: #16a34a;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            padding: 12px;
            background-color: #f9fafb;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        .feature-item:hover {
            background-color: #f0fdf4;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 12px;
            min-width: 30px;
        }
        .feature-text h4 {
            margin: 0 0 5px 0;
            color: #374151;
            font-size: 16px;
        }
        .feature-text p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }
        .cta-section {
            text-align: center;
            margin: 30px 0;
            padding: 25px;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-radius: 8px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            margin: 10px 0;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3);
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(22, 163, 74, 0.4);
        }
        .info-box {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .info-box strong {
            color: #92400e;
        }
        .footer {
            background-color: #f3f4f6;
            padding: 25px 20px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }
        .footer p {
            margin: 5px 0;
        }
        .social-links {
            margin: 15px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #16a34a;
            text-decoration: none;
            font-weight: 500;
        }
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #e5e7eb, transparent);
            margin: 25px 0;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 30px 20px;
            }
            .header h1 {
                font-size: 24px;
            }
            .detail-row {
                flex-direction: column;
            }
            .value {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <h1>🎉 Bienvenue sur FarmShop !</h1>
            <p>Votre compte a été créé avec succès</p>
        </div>

        <!-- Contenu principal -->
        <div class="content">
            <!-- Message de bienvenue -->
            <div class="welcome-box">
                <h2>Bonjour {{ $userName }} ! 👋</h2>
                <p>
                    Nous sommes ravis de vous accueillir dans la communauté FarmShop. 
                    Votre compte a été créé avec succès et vous pouvez maintenant profiter de tous nos services.
                </p>
            </div>

            <!-- Détails du compte -->
            <div class="account-details">
                <h3>📋 Informations de votre compte</h3>
                
                <div class="detail-row">
                    <span class="label">👤 Nom d'utilisateur :</span>
                    <span class="value">{{ $user->username }}</span>
                </div>
                
                @if($user->name)
                <div class="detail-row">
                    <span class="label">✨ Nom complet :</span>
                    <span class="value">{{ $user->name }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="label">📧 Email :</span>
                    <span class="value">{{ $userEmail }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">📅 Date de création :</span>
                    <span class="value">{{ $createdAt->format('d/m/Y à H:i') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">🎭 Rôle :</span>
                    <span class="value">{{ $user->role }}</span>
                </div>

                <div class="detail-row">
                    <span class="label">✅ Statut email :</span>
                    <span class="value">{{ $user->email_verified_at ? 'Vérifié ✓' : 'En attente de vérification' }}</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Ce que vous pouvez faire -->
            <div class="features">
                <h3>🌟 Ce que vous pouvez faire maintenant</h3>
                
                <div class="feature-item">
                    <div class="feature-icon">🛒</div>
                    <div class="feature-text">
                        <h4>Acheter des produits</h4>
                        <p>Parcourez notre catalogue de produits fermiers de qualité</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">🚜</div>
                    <div class="feature-text">
                        <h4>Louer du matériel agricole</h4>
                        <p>Accédez à notre service de location de matériel professionnel</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">📦</div>
                    <div class="feature-text">
                        <h4>Suivre vos commandes</h4>
                        <p>Consultez l'historique et le statut de toutes vos commandes</p>
                    </div>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">⭐</div>
                    <div class="feature-text">
                        <h4>Gérer vos favoris</h4>
                        <p>Sauvegardez vos produits préférés pour y accéder rapidement</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">💬</div>
                    <div class="feature-text">
                        <h4>Contacter le support</h4>
                        <p>Notre équipe est là pour répondre à toutes vos questions</p>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Information importante -->
            @if(!$user->email_verified_at)
            <div class="info-box">
                <strong>📧 Vérification d'email requise</strong><br>
                Pour accéder à toutes les fonctionnalités, veuillez vérifier votre adresse email en cliquant sur le lien que nous vous avons envoyé séparément. 
                Si vous ne l'avez pas reçu, vérifiez votre dossier spam.
            </div>
            @endif

            <!-- Call to action -->
            <div class="cta-section">
                <h3 style="color: #16a34a; margin-top: 0;">Prêt à commencer ? 🚀</h3>
                <p style="color: #374151; margin-bottom: 20px;">
                    Connectez-vous maintenant et découvrez tout ce que FarmShop a à offrir
                </p>
                <a href="{{ url('/login') }}" class="btn">
                    Se connecter à mon compte
                </a>
                <p style="margin-top: 15px; font-size: 14px; color: #6b7280;">
                    Ou visitez notre page d'accueil : 
                    <a href="{{ url('/') }}" style="color: #16a34a; text-decoration: none; font-weight: 500;">
                        {{ url('/') }}
                    </a>
                </p>
            </div>

            <!-- Aide et support -->
            <div style="margin-top: 30px; padding: 20px; background-color: #f0f9ff; border-radius: 8px;">
                <h4 style="color: #0369a1; margin: 0 0 10px 0;">💡 Besoin d'aide ?</h4>
                <p style="margin: 0; color: #374151; font-size: 14px;">
                    Si vous avez des questions ou rencontrez des difficultés, n'hésitez pas à :
                </p>
                <ul style="margin: 10px 0; color: #374151; font-size: 14px;">
                    <li>Consulter notre <a href="{{ url('/help') }}" style="color: #0369a1;">centre d'aide</a></li>
                    <li>Nous contacter via le <a href="{{ url('/contact') }}" style="color: #0369a1;">formulaire de contact</a></li>
                    <li>Nous envoyer un email à <a href="mailto:s.mef2703@gmail.com" style="color: #0369a1;">s.mef2703@gmail.com</a></li>
                </ul>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            <p><strong>FarmShop</strong> - Votre partenaire agricole de confiance</p>
            <div class="social-links">
                <a href="{{ url('/') }}">🏠 Accueil</a>
                <a href="{{ url('/products') }}">🛍️ Boutique</a>
                <a href="{{ url('/rentals') }}">🚜 Locations</a>
                <a href="{{ url('/contact') }}">📧 Contact</a>
            </div>
            <p style="margin-top: 15px; font-size: 12px; color: #9ca3af;">
                📧 s.mef2703@gmail.com | 📞 +32 2 123 45 67
            </p>
            <p style="margin-top: 10px; font-size: 12px;">
                Cet email a été envoyé automatiquement suite à la création de votre compte.
            </p>
            <p style="font-size: 11px; color: #9ca3af;">
                © {{ date('Y') }} FarmShop. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>
