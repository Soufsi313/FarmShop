@extends('layouts.admin')

@section('title', 'Créer une newsletter - Dashboard Admin')
@section('page-title', 'Créer une newsletter')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Créer une nouvelle newsletter
                    </h1>
                    <p class="mt-2 text-purple-100">
                        Créez et configurez votre campagne email marketing
                    </p>
                </div>
                <div class="text-right">
                    <a href="{{ route('admin.newsletters.index') }}" 
                       class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
                        ← Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.newsletters.store') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        
        <!-- Informations de base -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations de base</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre de la newsletter *
                    </label>
                    <input type="text" name="title" id="title" required
                           value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Ex: Newsletter janvier 2025">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                        Sujet de l'email *
                    </label>
                    <input type="text" name="subject" id="subject" required
                           value="{{ old('subject') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Ex: Découvrez nos nouveautés du mois">
                    @error('subject')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                    Extrait / Résumé
                </label>
                <textarea name="excerpt" id="excerpt" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                          placeholder="Court résumé de votre newsletter...">{{ old('excerpt') }}</textarea>
                @error('excerpt')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Contenu -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Contenu de la newsletter</h2>
            
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Contenu de la newsletter *
                </label>
                <div id="editor-container" style="height: 400px;" class="border border-gray-300 rounded-lg"></div>
                <textarea name="content" id="content" style="display: none;" required>{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-500 mt-2">
                    Utilisez l'éditeur pour formater votre newsletter comme dans Word.
                </p>
            </div>
        </div>

        <!-- Image et options -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Options avancées</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                        Image de couverture
                    </label>
                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('featured_image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Format accepté: JPG, PNG (max 2MB)</p>
                </div>

                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                        Tags (séparés par des virgules)
                    </label>
                    <input type="text" name="tags" id="tags"
                           value="{{ old('tags') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                           placeholder="Ex: promotion, nouveauté, été">
                    @error('tags')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Programmation -->
        <div class="bg-white rounded-lg shadow border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Programmation de l'envoi</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Statut *
                    </label>
                    <select name="status" id="status" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                            Brouillon (sauvegarder sans envoyer)
                        </option>
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>
                            Programmer un envoi
                        </option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="scheduled_date_container" style="display: none;">
                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">
                        Date et heure d'envoi
                    </label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                           value="{{ old('scheduled_at') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    @error('scheduled_at')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center">
            <a href="{{ route('admin.newsletters.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors">
                Annuler
            </a>
            
            <div class="flex gap-3">
                <button type="submit" name="action" value="save_draft"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-3 rounded-lg transition-colors">
                    Sauvegarder comme brouillon
                </button>
                
                <button type="submit" name="action" value="save_and_schedule"
                        class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition-colors">
                    Créer la newsletter
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const scheduledContainer = document.getElementById('scheduled_date_container');
    
    function toggleScheduledDate() {
        if (statusSelect.value === 'scheduled') {
            scheduledContainer.style.display = 'block';
            document.getElementById('scheduled_at').required = true;
        } else {
            scheduledContainer.style.display = 'none';
            document.getElementById('scheduled_at').required = false;
        }
    }
    
    statusSelect.addEventListener('change', toggleScheduledDate);
    toggleScheduledDate(); // Initialiser l'état
});
</script>

<!-- Quill.js Editor (Gratuit et sans clé API) -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuration de l'éditeur Quill
    var quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'font': [] }],
                [{ 'size': ['small', false, 'large', 'huge'] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'direction': 'rtl' }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                ['clean']
            ]
        },
        placeholder: 'Commencez à écrire votre newsletter...',
        formats: [
            'header', 'font', 'size',
            'bold', 'italic', 'underline', 'strike',
            'color', 'background',
            'script', 'super', 'sub',
            'list', 'bullet', 'indent',
            'align', 'direction',
            'blockquote', 'code-block',
            'link', 'image', 'video'
        ]
    });

    // Templates prédéfinis
    const templates = {
        basic: `
            <h1>📧 Newsletter FarmShop</h1>
            <p><strong>Bonjour,</strong></p>
            <p>Voici les dernières nouvelles de FarmShop, votre partenaire pour l'agriculture moderne.</p>
            
            <h2>🌱 Nos Nouveautés</h2>
            <p>Découvrez notre sélection de matériel agricole de qualité...</p>
            
            <h2>💡 Conseil de la Semaine</h2>
            <p>Nos experts partagent leurs conseils pour optimiser vos cultures...</p>
            
            <p><strong>Cordialement,</strong><br>
            L'équipe FarmShop</p>
        `,
        promo: `
            <div style="background-color: #d4edda; padding: 20px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                <h1 style="color: #28a745;">🎉 Offre Spéciale FarmShop !</h1>
                <p style="font-size: 18px;"><strong>Profitez de nos promotions exceptionnelles</strong></p>
            </div>
            
            <h2>🏷️ Produits en Promotion</h2>
            <p>Découvrez notre sélection de matériel agricole à prix réduit :</p>
            <ul>
                <li>Tracteurs - Jusqu'à 15% de réduction</li>
                <li>Outils de jardinage - À partir de 25€</li>
                <li>Équipements de protection - Offre spéciale</li>
            </ul>
            
            <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;">
                <p><strong>⏰ Offre limitée !</strong> Valable jusqu'au [DATE]</p>
            </div>
            
            <p><a href="#" style="background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">🛒 Voir les offres</a></p>
        `,
        info: `
            <h1>📰 Actualités FarmShop</h1>
            
            <h2>📈 À la Une</h2>
            <p>Les dernières nouvelles du secteur agricole et nos actualités...</p>
            
            <h2>💡 Conseil de la Semaine</h2>
            <blockquote style="border-left: 4px solid #28a745; padding-left: 15px; font-style: italic;">
                "Un bon agriculteur sait que la préparation du sol est la clé d'une récolte réussie."
            </blockquote>
            <p>Nos experts vous expliquent comment bien préparer vos terres...</p>
            
            <h2>🔧 Focus Produit</h2>
            <p><strong>Zoom sur :</strong> [Nom du produit]</p>
            <p>Caractéristiques principales, avantages et conseils d'utilisation...</p>
            
            <h2>📅 Événements à Venir</h2>
            <ul>
                <li>Salon de l'Agriculture - [Date]</li>
                <li>Formation maintenance - [Date]</li>
                <li>Démonstration matériel - [Date]</li>
            </ul>
        `
    };

    // Boutons pour insérer des templates
    const toolbar = document.querySelector('.ql-toolbar');
    const templateGroup = document.createElement('span');
    templateGroup.className = 'ql-formats';
    templateGroup.innerHTML = `
        <select class="ql-template" title="Templates">
            <option value="">Templates</option>
            <option value="basic">Newsletter Basique</option>
            <option value="promo">Newsletter Promotionnelle</option>
            <option value="info">Newsletter Informative</option>
        </select>
    `;
    toolbar.appendChild(templateGroup);

    // Gestionnaire pour les templates
    const templateSelect = toolbar.querySelector('.ql-template');
    templateSelect.addEventListener('change', function() {
        if (this.value && templates[this.value]) {
            quill.root.innerHTML = templates[this.value];
            updateHiddenInput();
            this.value = '';
        }
    });

    // Fonction pour mettre à jour le champ caché
    function updateHiddenInput() {
        document.getElementById('content').value = quill.root.innerHTML;
    }

    // Mettre à jour le champ caché à chaque modification
    quill.on('text-change', function() {
        updateHiddenInput();
    });

    // Charger le contenu initial si présent
    const initialContent = document.getElementById('content').value;
    if (initialContent) {
        quill.root.innerHTML = initialContent;
    }

    // S'assurer que le contenu est mis à jour avant soumission
    document.querySelector('form').addEventListener('submit', function() {
        updateHiddenInput();
    });
});
</script>

<style>
/* Personnalisation de l'éditeur Quill pour FarmShop */
.ql-toolbar {
    border-top: 1px solid #ccc;
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    background: #f8f9fa;
}

.ql-container {
    border-bottom: 1px solid #ccc;
    border-left: 1px solid #ccc;
    border-right: 1px solid #ccc;
    font-family: 'Arial', sans-serif;
}

.ql-editor {
    min-height: 300px;
    font-size: 14px;
    line-height: 1.6;
}

.ql-template {
    background: #28a745;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
}

/* Style pour les éléments dans l'éditeur */
.ql-editor h1 { color: #28a745; border-bottom: 2px solid #28a745; padding-bottom: 5px; }
.ql-editor h2 { color: #495057; border-left: 4px solid #28a745; padding-left: 10px; }
.ql-editor blockquote { border-left: 4px solid #28a745; padding-left: 15px; font-style: italic; margin: 15px 0; }
</style>
@endsection
