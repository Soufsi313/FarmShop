# Tests Unitaires: Systeme Email (09_Email)

## Description

Ce dossier contient les tests unitaires pour le systeme d'email de FarmShop, incluant les classes Mailable, les Notifications et les templates Blade.

## Structure des Tests

### 1. test_mailable_classes.php
Teste les classes Mailable (emails envoyables).

**Classes testees:**
- WelcomeEmail - Email de bienvenue
- RentalConfirmationMail - Confirmation de location
- RentalStartedMail - Debut de location
- RentalEndReminderMail - Rappel de fin de location
- RentalEndedMail - Fin de location
- RentalOverdueMail - Location en retard
- RentalOrderConfirmed - Commande de location confirmee
- RentalOrderCancelled - Commande de location annulee
- RentalOrderCompleted - Commande de location completee
- RentalOrderInspection - Inspection de location
- NewsletterMail - Email de newsletter
- AccountDeletedEmail - Compte supprime
- AccountDeletionNotification - Notification de suppression
- VisitorContactConfirmation - Confirmation de contact visiteur
- VisitorMessageReply - Reponse au message visiteur
- NewContactNotification - Nouvelle notification de contact

**Validations:**
- Structure des classes (heritage Mailable)
- Methodes requises (envelope, content, attachments)
- Proprietes publiques
- Configuration des enveloppes (sujet, destinataire, replyTo)
- Configuration du contenu (vues HTML/texte, variables)
- Traits utilises (Queueable, SerializesModels)

### 2. test_notification_classes.php
Teste les classes Notification.

**Classes testees:**
- VerifyEmailNotification - Verification d'email
- ConfirmAccountDeletionNotification - Confirmation de suppression de compte

**Validations:**
- Heritage des classes Notification
- Methodes requises (via, toMail)
- Canaux de notification
- Configuration des messages
- Interface ShouldQueue (mise en file d'attente)
- Personnalisation des messages
- Expiration des liens (60 minutes)

### 3. test_email_templates.php
Teste l'existence et la structure des templates email (vues Blade).

**Categories de templates:**
- Templates de location (rental)
  - rental-confirmation.blade.php
  - rental-started.blade.php
  - rental-end-reminder.blade.php
  - rental-ended.blade.php
  - rental-overdue.blade.php

- Templates de commande location
  - rental-order-confirmed.blade.php
  - rental-order-cancelled.blade.php
  - rental-order-completed.blade.php
  - rental-order-inspection.blade.php

- Templates newsletter
  - newsletter.blade.php
  - newsletter-text.blade.php

- Templates utilisateur
  - welcome.blade.php
  - account-deleted.blade.php
  - visitor-contact-confirmation.blade.php
  - visitor-message-reply.blade.php

**Validations:**
- Existence des fichiers templates
- Taille des fichiers (non vides)
- Directives Blade (@extends, @section, @if, @foreach)
- Variables attendues dans les templates
- Versions texte (alternative text)
- Sous-dossiers (account/, rental/)
- Statistiques globales (nombre, taille)

## Execution

### Test individuel
```bash
php TestUnit/09_Email/test_mailable_classes.php
php TestUnit/09_Email/test_notification_classes.php
php TestUnit/09_Email/test_email_templates.php
```

### Tous les tests
```bash
php TestUnit/09_Email/run_all_tests.php
```

## Criteres de Reussite

### Classes Mailable
- Toutes les classes Mailable existent
- Heritent de Illuminate\Mail\Mailable
- Implementent les methodes envelope(), content(), attachments()
- Utilisent les traits Queueable et SerializesModels
- Configurent correctement les sujets et vues

### Classes Notification
- Toutes les classes Notification existent
- Heritent de Illuminate\Notifications\Notification
- Implementent les methodes via() et toMail()
- VerifyEmailNotification etend VerifyEmail
- Messages personnalises en francais

### Templates Email
- Tous les templates existent dans resources/views/emails/
- Fichiers non vides
- Contiennent les directives Blade appropriees
- Incluent les variables attendues
- Versions texte disponibles pour les emails importants

## Fonctionnalites Email

### Emails de Location
- Confirmation de reservation
- Notification de debut de location
- Rappel de fin de location
- Notification de fin de location
- Alerte de retard

### Emails de Commande
- Confirmation de commande
- Annulation de commande
- Completion de commande
- Rapport d'inspection

### Emails Newsletter
- Envoi de newsletter HTML
- Version texte alternative
- URL de tracking
- URL de desinscription

### Emails Utilisateur
- Bienvenue
- Verification d'email
- Suppression de compte
- Contact visiteur
- Reponses aux messages

## Notes Techniques

### Configuration Mail
- Driver: Configure dans config/mail.php
- Queue: Certains emails en file d'attente (ShouldQueue)
- Templates: resources/views/emails/
- Langues: Francais

### Bonnes Pratiques
- Versions HTML et texte pour accessibilite
- Sujets clairs et descriptifs
- Variables bien nommees et documentees
- Liens de desinscription pour newsletters
- Expiration des liens securises (60 min)

### Performance
- Emails en file d'attente pour envois multiples (newsletters)
- Emails synchrones pour actions critiques (verification)
- Optimisation des templates
- Cache des vues Blade

## Resultats Attendus

Execution rapide (< 1 seconde) avec validation complete du systeme email.
