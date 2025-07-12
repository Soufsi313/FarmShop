<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Models\NewsletterSubscription;
use App\Models\NewsletterSend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NewsletterController extends Controller
{
    /**
     * Afficher la liste des newsletters (Admin seulement)
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

            $query = Newsletter::with(['creator', 'updater'])->notTemplates();

            // Filtres
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            if ($request->has('tag') && !empty($request->tag)) {
                $query->byTag($request->tag);
            }

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhere('excerpt', 'like', "%{$search}%");
                });
            }

            // Tri
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $newsletters = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $newsletters,
                'statistics' => Newsletter::getStatistics()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des newsletters: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des newsletters'
            ], 500);
        }
    }

    /**
     * Créer une nouvelle newsletter (Admin seulement)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            // Validation des données
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'content' => 'required|string',
                'excerpt' => 'nullable|string|max:500',
                'featured_image' => 'nullable|url',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'is_template' => 'boolean',
                'template_name' => 'nullable|string|max:100',
                'scheduled_at' => 'nullable|date|after:now',
                'send_immediately' => 'boolean'
            ], [
                'title.required' => 'Le titre est obligatoire',
                'subject.required' => 'Le sujet est obligatoire',
                'content.required' => 'Le contenu est obligatoire',
                'scheduled_at.after' => 'La date de programmation doit être dans le futur'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Créer la newsletter
            $newsletter = Newsletter::create([
                'title' => $request->title,
                'subject' => $request->subject,
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'featured_image' => $request->featured_image,
                'tags' => $request->tags ?? [],
                'is_template' => $request->get('is_template', false),
                'template_name' => $request->template_name,
                'created_by' => auth()->id()
            ]);

            // Gestion de l'envoi
            if ($request->get('send_immediately', false)) {
                $success = $newsletter->sendNow();
                $message = $success ? 
                    'Newsletter créée et envoyée avec succès' : 
                    'Newsletter créée mais aucun abonné trouvé pour l\'envoi';
            } elseif ($request->scheduled_at) {
                $scheduledAt = Carbon::parse($request->scheduled_at);
                $newsletter->scheduleAndSend($scheduledAt);
                $message = 'Newsletter créée et programmée avec succès';
            } else {
                $message = 'Newsletter créée en brouillon avec succès';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $newsletter->fresh(['creator', 'updater'])
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la newsletter'
            ], 500);
        }
    }

    /**
     * Afficher une newsletter spécifique (Admin seulement)
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

            $newsletter = Newsletter::with(['creator', 'updater', 'sends.user'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $newsletter
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de la newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Newsletter non trouvée'
            ], 404);
        }
    }

    /**
     * Mettre à jour une newsletter (Admin seulement)
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $newsletter = Newsletter::findOrFail($id);

            // Vérifier si la newsletter peut être modifiée
            if (!$newsletter->canBeEdited()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette newsletter ne peut plus être modifiée'
                ], 400);
            }

            // Validation des données
            $validator = Validator::make($request->all(), [
                'title' => 'sometimes|required|string|max:255',
                'subject' => 'sometimes|required|string|max:255',
                'content' => 'sometimes|required|string',
                'excerpt' => 'nullable|string|max:500',
                'featured_image' => 'nullable|url',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'template_name' => 'nullable|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Mettre à jour la newsletter
            $newsletter->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Newsletter mise à jour avec succès',
                'data' => $newsletter->fresh(['creator', 'updater'])
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la newsletter'
            ], 500);
        }
    }

    /**
     * Supprimer une newsletter (Admin seulement)
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

            $newsletter = Newsletter::findOrFail($id);

            // Vérifier si la newsletter peut être supprimée
            if ($newsletter->status === 'sent') {
                return response()->json([
                    'success' => false,
                    'message' => 'Une newsletter déjà envoyée ne peut pas être supprimée'
                ], 400);
            }

            $newsletter->delete();

            return response()->json([
                'success' => true,
                'message' => 'Newsletter supprimée avec succès'
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la newsletter'
            ], 500);
        }
    }

    /**
     * Envoyer immédiatement une newsletter (Admin seulement)
     */
    public function sendNow(Request $request, $id): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $newsletter = Newsletter::findOrFail($id);

            if (!$newsletter->canBeSent()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette newsletter ne peut pas être envoyée'
                ], 400);
            }

            $success = $newsletter->sendNow();

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Newsletter envoyée avec succès',
                    'data' => $newsletter->fresh()
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun abonné trouvé pour l\'envoi'
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de la newsletter'
            ], 500);
        }
    }

    /**
     * Programmer l'envoi d'une newsletter (Admin seulement)
     */
    public function schedule(Request $request, $id): JsonResponse
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
                'scheduled_at' => 'required|date|after:now'
            ], [
                'scheduled_at.required' => 'La date de programmation est obligatoire',
                'scheduled_at.after' => 'La date de programmation doit être dans le futur'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            $newsletter = Newsletter::findOrFail($id);

            if (!$newsletter->canBeScheduled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette newsletter ne peut pas être programmée'
                ], 400);
            }

            $scheduledAt = Carbon::parse($request->scheduled_at);
            $newsletter->scheduleAndSend($scheduledAt);

            return response()->json([
                'success' => true,
                'message' => 'Newsletter programmée avec succès',
                'data' => $newsletter->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la programmation de la newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la programmation de la newsletter'
            ], 500);
        }
    }

    /**
     * Annuler l'envoi programmé d'une newsletter (Admin seulement)
     */
    public function cancel(Request $request, $id): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $newsletter = Newsletter::findOrFail($id);

            if (!$newsletter->canBeCancelled()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cette newsletter ne peut pas être annulée'
                ], 400);
            }

            $newsletter->cancel();

            return response()->json([
                'success' => true,
                'message' => 'Envoi de la newsletter annulé avec succès',
                'data' => $newsletter->fresh()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'annulation de la newsletter: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'annulation de la newsletter'
            ], 500);
        }
    }

    /**
     * Dupliquer une newsletter comme template (Admin seulement)
     */
    public function duplicateAsTemplate(Request $request, $id): JsonResponse
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
                'template_name' => 'required|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nom du template obligatoire',
                    'errors' => $validator->errors()
                ], 422);
            }

            $newsletter = Newsletter::findOrFail($id);
            $template = $newsletter->duplicateAsTemplate($request->template_name);

            return response()->json([
                'success' => true,
                'message' => 'Template créé avec succès',
                'data' => $template
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la duplication comme template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du template'
            ], 500);
        }
    }

    /**
     * Obtenir les statistiques des newsletters (Admin seulement)
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

            $newsletterStats = Newsletter::getStatistics();
            $subscriptionStats = NewsletterSubscription::getStatistics();

            return response()->json([
                'success' => true,
                'data' => [
                    'newsletters' => $newsletterStats,
                    'subscriptions' => $subscriptionStats
                ]
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
     * Obtenir les templates disponibles (Admin seulement)
     */
    public function templates(): JsonResponse
    {
        try {
            // Vérifier que l'utilisateur est admin
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé'
                ], 403);
            }

            $templates = Newsletter::templates()
                ->with(['creator'])
                ->orderBy('template_name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $templates
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des templates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des templates'
            ], 500);
        }
    }

    /**
     * Créer une newsletter à partir d'un template (Admin seulement)
     */
    public function createFromTemplate(Request $request, $templateId): JsonResponse
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
                'title' => 'required|string|max:255',
                'subject' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données de validation incorrectes',
                    'errors' => $validator->errors()
                ], 422);
            }

            $template = Newsletter::templates()->findOrFail($templateId);
            $newsletter = Newsletter::createFromTemplate($template, $validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Newsletter créée à partir du template avec succès',
                'data' => $newsletter->fresh(['creator'])
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création depuis template: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création depuis le template'
            ], 500);
        }
    }
}
