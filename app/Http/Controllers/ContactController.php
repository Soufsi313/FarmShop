<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Afficher la liste des contacts (Admin seulement)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $query = Contact::with('admin');

            // Filtres
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            if ($request->has('reason') && !empty($request->reason)) {
                $query->where('reason', $request->reason);
            }

            if ($request->has('priority') && !empty($request->priority)) {
                $query->where('priority', $request->priority);
            }

            if ($request->has('is_read') && $request->is_read !== '') {
                $query->where('is_read', (bool) $request->is_read);
            }

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('message', 'like', "%{$search}%");
                });
            }

            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $contacts = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $contacts,
                'statistics' => Contact::getStatistics()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des contacts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des contacts'
            ], 500);
        }
    }

    /**
     * Créer un nouveau contact (Formulaire public)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validation des données
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'subject' => 'required|string|max:255',
                'reason' => 'required|in:' . implode(',', array_keys(Contact::REASONS)),
                'message' => 'required|string|min:10|max:2000',
                'priority' => 'nullable|in:' . implode(',', array_keys(Contact::PRIORITIES))
            ], [
                'name.required' => 'Le nom est obligatoire',
                'email.required' => 'L\'adresse email est obligatoire',
                'email.email' => 'L\'adresse email doit être valide',
                'subject.required' => 'L\'objet est obligatoire',
                'reason.required' => 'La raison de contact est obligatoire',
                'reason.in' => 'La raison de contact sélectionnée n\'est pas valide',
                'message.required' => 'Le message est obligatoire',
                'message.min' => 'Le message doit contenir au moins 10 caractères',
                'message.max' => 'Le message ne peut pas dépasser 2000 caractères'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Collecter les métadonnées
            $metadata = [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'submitted_at' => now()->toISOString()
            ];

            // Créer le contact
            $contact = Contact::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'reason' => $request->reason,
                'message' => $request->message,
                'priority' => $request->priority ?? 'medium',
                'metadata' => $metadata
            ]);

            // Envoyer une notification email à l'admin (optionnel)
            $this->sendNewContactNotification($contact);

            return response()->json([
                'success' => true,
                'message' => 'Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.',
                'data' => [
                    'contact_id' => $contact->id,
                    'reference' => 'CONTACT-' . str_pad($contact->id, 6, '0', STR_PAD_LEFT)
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du contact: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi du message. Veuillez réessayer.'
            ], 500);
        }
    }

    /**
     * Afficher un contact spécifique (Admin seulement)
     */
    public function show($id): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $contact = Contact::with('admin')->findOrFail($id);

            // Marquer comme lu si ce n'est pas déjà fait
            if (!$contact->is_read) {
                $contact->markAsRead(auth()->id());
            }

            return response()->json([
                'success' => true,
                'data' => $contact
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du contact: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Contact non trouvé'
            ], 404);
        }
    }

    /**
     * Répondre à un contact (Admin seulement)
     */
    public function respond(Request $request, $id): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'response' => 'required|string|min:10|max:5000',
                'send_email' => 'boolean'
            ], [
                'response.required' => 'La réponse est obligatoire',
                'response.min' => 'La réponse doit contenir au moins 10 caractères',
                'response.max' => 'La réponse ne peut pas dépasser 5000 caractères'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contact = Contact::findOrFail($id);

            // Ajouter la réponse
            $contact->addAdminResponse($request->response, auth()->id());

            // Envoyer l'email si demandé
            if ($request->get('send_email', true)) {
                $this->sendResponseEmail($contact);
            }

            return response()->json([
                'success' => true,
                'message' => 'Réponse envoyée avec succès',
                'data' => $contact->fresh('admin')
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la réponse au contact: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de la réponse'
            ], 500);
        }
    }

    /**
     * Mettre à jour le statut d'un contact (Admin seulement)
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:' . implode(',', array_keys(Contact::STATUSES))
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Statut invalide',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contact = Contact::findOrFail($id);
            $contact->changeStatus($request->status);

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès',
                'data' => $contact->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du statut: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du statut'
            ], 500);
        }
    }

    /**
     * Mettre à jour la priorité d'un contact (Admin seulement)
     */
    public function updatePriority(Request $request, $id): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Validation
            $validator = Validator::make($request->all(), [
                'priority' => 'required|in:' . implode(',', array_keys(Contact::PRIORITIES))
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Priorité invalide',
                    'errors' => $validator->errors()
                ], 422);
            }

            $contact = Contact::findOrFail($id);
            $contact->changePriority($request->priority);

            return response()->json([
                'success' => true,
                'message' => 'Priorité mise à jour avec succès',
                'data' => $contact->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la priorité: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la priorité'
            ], 500);
        }
    }

    /**
     * Supprimer un contact (Admin seulement)
     */
    public function destroy($id): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $contact = Contact::findOrFail($id);
            $contact->delete();

            return response()->json([
                'success' => true,
                'message' => 'Contact supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du contact: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du contact'
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des contacts (Admin seulement)
     */
    public function statistics(): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $statistics = Contact::getStatistics();

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques'
            ], 500);
        }
    }

    /**
     * Marquer plusieurs contacts comme lus (Admin seulement)
     */
    public function markAsRead(Request $request): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'contact_ids' => 'required|array',
                'contact_ids.*' => 'exists:contacts,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'IDs de contacts invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            Contact::whereIn('id', $request->contact_ids)
                   ->where('is_read', false)
                   ->update([
                       'is_read' => true,
                       'read_at' => now(),
                       'admin_id' => auth()->id()
                   ]);

            return response()->json([
                'success' => true,
                'message' => 'Contacts marqués comme lus avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage comme lu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du marquage comme lu'
            ], 500);
        }
    }

    /**
     * Envoyer une notification de nouveau contact aux admins
     */
    private function sendNewContactNotification(Contact $contact): void
    {
        try {
            // Configuration Gmail SMTP sera ajoutée dans la configuration email
            // Pour l'instant, on log juste l'événement
            Log::info('Nouveau contact reçu', [
                'contact_id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'subject' => $contact->subject,
                'reason' => $contact->reason
            ]);

            // TODO: Implémenter l'envoi d'email aux admins
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer l'email de réponse au visiteur
     */
    private function sendResponseEmail(Contact $contact): void
    {
        try {
            // Configuration Gmail SMTP sera ajoutée dans la configuration email
            // Pour l'instant, on log juste l'événement
            Log::info('Réponse envoyée par email', [
                'contact_id' => $contact->id,
                'email' => $contact->email,
                'admin_id' => $contact->admin_id
            ]);

            // Marquer l'email comme envoyé
            $contact->markEmailSent();

            // TODO: Implémenter l'envoi d'email avec la réponse
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de l\'email de réponse: ' . $e->getMessage());
        }
    }
}
