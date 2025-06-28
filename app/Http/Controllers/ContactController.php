<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\User;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Afficher le formulaire de contact (pour visiteurs).
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $reasons = Contact::getReasons();
        
        return view('contact.create', compact('reasons'));
    }

    /**
     * Enregistrer une nouvelle demande de contact (visiteurs).
     *
     * @param  \App\Http\Requests\ContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactRequest $request)
    {
        try {
            // Gérer les fichiers joints si présents
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('contact-attachments', 'public');
                    $attachments[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType()
                    ];
                }
            }

            // Créer la demande de contact
            $contact = Contact::createFromRequest([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'reason' => $request->reason,
                'message' => $request->message,
                'attachments' => !empty($attachments) ? $attachments : null
            ]);

            // Envoyer notification email aux admins si urgent
            if ($contact->is_urgent) {
                $this->notifyAdminsUrgentContact($contact);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Votre demande a été envoyée avec succès. Nous vous répondrons dans les plus brefs délais.',
                    'contact_id' => $contact->id
                ], 201);
            }

            return redirect()->back()
                ->with('success', 'Votre demande a été envoyée avec succès. Nous vous répondrons dans les plus brefs délais.')
                ->with('contact_id', $contact->id);

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du contact: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'envoi de votre demande. Veuillez réessayer.')
                ->withInput();
        }
    }

    /**
     * Afficher la liste des contacts (pour admins).
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Middleware pour vérifier que l'utilisateur est admin
        $this->middleware(['auth', 'permission:manage contacts|admin']);

        $query = Contact::with('assignedAdmin')->orderBy('created_at', 'desc');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('urgent')) {
            $query->urgent();
        }

        if ($request->filled('overdue')) {
            $query->overdue();
        }

        if ($request->filled('search')) {
            $query = Contact::search($request->search);
        }

        $contacts = $query->paginate(20);
        $statistics = Contact::getStatistics();
        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'contacts' => $contacts,
                    'statistics' => $statistics,
                    'filters' => [
                        'statuses' => Contact::getStatuses(),
                        'reasons' => Contact::getReasons(),
                        'admins' => $admins
                    ]
                ]
            ]);
        }

        return view('admin.contacts.index', compact('contacts', 'statistics', 'admins'));
    }

    /**
     * Afficher un contact spécifique.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        // Middleware pour vérifier que l'utilisateur est admin
        $this->middleware(['auth', 'permission:manage contacts|admin']);

        $contact->load('assignedAdmin');
        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $contact
            ]);
        }

        return view('admin.contacts.show', compact('contact', 'admins'));
    }

    /**
     * Mettre à jour un contact (pour admins).
     *
     * @param  \App\Http\Requests\UpdateContactRequest  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $updated = false;

        // Mettre à jour le statut
        if ($request->has('status') && $request->status !== $contact->status) {
            $contact->status = $request->status;
            $updated = true;

            // Marquer comme ayant reçu une réponse si passage à "en cours" ou "résolu"
            if (in_array($request->status, [Contact::STATUS_IN_PROGRESS, Contact::STATUS_RESOLVED]) && !$contact->responded_at) {
                $contact->markResponded();
            }
        }

        // Assigner à un admin
        if ($request->has('assigned_to')) {
            $contact->assigned_to = $request->assigned_to;
            $updated = true;
        }

        // Ajouter une note admin
        if ($request->filled('admin_notes')) {
            $contact->addAdminNote($request->admin_notes);
            $updated = true;
        }

        if ($updated) {
            $contact->save();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact mis à jour avec succès.',
                'data' => $contact->refresh()->load('assignedAdmin')
            ]);
        }

        return redirect()->route('admin.contacts.show', $contact)
            ->with('success', 'Contact mis à jour avec succès.');
    }

    /**
     * Supprimer un contact (pour admins).
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        // Middleware pour vérifier que l'utilisateur est admin
        $this->middleware(['auth', 'permission:manage contacts|admin']);

        // Supprimer les fichiers joints s'ils existent
        if ($contact->attachments) {
            foreach ($contact->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $contact->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact supprimé avec succès.'
            ]);
        }

        return redirect()->route('admin.contacts.index')
            ->with('success', 'Contact supprimé avec succès.');
    }

    /**
     * Actions rapides pour les admins.
     */
    public function markInProgress(Contact $contact)
    {
        $this->middleware(['auth', 'permission:manage contacts|admin']);

        $contact->markInProgress(auth()->id());

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact marqué en cours de traitement.',
                'data' => $contact->refresh()
            ]);
        }

        return redirect()->back()->with('success', 'Contact marqué en cours de traitement.');
    }

    public function markResolved(Request $request, Contact $contact)
    {
        $this->middleware(['auth', 'permission:manage contacts|admin']);

        $validator = Validator::make($request->all(), [
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        $contact->markResolved($request->admin_notes);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact marqué comme résolu.',
                'data' => $contact->refresh()
            ]);
        }

        return redirect()->back()->with('success', 'Contact marqué comme résolu.');
    }

    public function close(Request $request, Contact $contact)
    {
        $this->middleware(['auth', 'permission:manage contacts|admin']);

        $validator = Validator::make($request->all(), [
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        $contact->close($request->admin_notes);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact fermé.',
                'data' => $contact->refresh()
            ]);
        }

        return redirect()->back()->with('success', 'Contact fermé.');
    }

    /**
     * Assigner un contact à un admin.
     */
    public function assign(Request $request, Contact $contact)
    {
        $this->middleware(['auth', 'permission:manage contacts|admin']);

        $validator = Validator::make($request->all(), [
            'assigned_to' => 'required|integer|exists:users,id'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        $contact->assignTo($request->assigned_to);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Contact assigné avec succès.',
                'data' => $contact->refresh()->load('assignedAdmin')
            ]);
        }

        return redirect()->back()->with('success', 'Contact assigné avec succès.');
    }

    /**
     * Obtenir les statistiques des contacts.
     */
    public function statistics()
    {
        $this->middleware(['auth', 'permission:manage contacts|admin']);

        $statistics = Contact::getStatistics();

        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Télécharger un fichier joint.
     */
    public function downloadAttachment(Contact $contact, $attachmentIndex)
    {
        $this->middleware(['auth', 'permission:manage contacts|admin']);

        if (!$contact->attachments || !isset($contact->attachments[$attachmentIndex])) {
            abort(404, 'Fichier non trouvé.');
        }

        $attachment = $contact->attachments[$attachmentIndex];
        
        if (!Storage::disk('public')->exists($attachment['path'])) {
            abort(404, 'Fichier non trouvé sur le serveur.');
        }

        return Storage::disk('public')->download(
            $attachment['path'], 
            $attachment['original_name']
        );
    }

    /**
     * Notifier les admins d'un contact urgent.
     */
    private function notifyAdminsUrgentContact(Contact $contact)
    {
        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get();

        foreach ($admins as $admin) {
            // TODO: Envoyer email de notification
            // Mail::to($admin->email)->send(new UrgentContactNotification($contact));
        }
    }
}
