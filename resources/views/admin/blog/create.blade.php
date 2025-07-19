@extends('layouts.admin')

@section('title', 'Créer un Article - FarmShop Admin')
@section('page-title', 'Créer un Article')

@push('styles')
<style>
    /* Amélioration des champs de formulaire */
    .form-input {
        padding: 12px 16px;
        font-size: 16px;
        line-height: 1.5;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        background-color: #fafafa;
    }

    .form-input:focus {
        outline: none;
        border-color: #10B981;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        transform: translateY(-1px);
    }

    .form-input:hover {
        border-color: #d1d5db;
        background-color: #ffffff;
    }

    .form-select {
        padding: 12px 16px 12px 16px;
        font-size: 16px;
        line-height: 1.5;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        background-color: #fafafa;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px 12px;
        padding-right: 48px;
        appearance: none;
    }

    .form-select:focus {
        outline: none;
        border-color: #10B981;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        transform: translateY(-1px);
    }

    .form-select:hover {
        border-color: #d1d5db;
        background-color: #ffffff;
    }

    .form-textarea {
        padding: 12px 16px;
        font-size: 16px;
        line-height: 1.5;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        background-color: #fafafa;
        resize: vertical;
        min-height: 120px;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #10B981;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        transform: translateY(-1px);
    }

    .form-textarea:hover {
        border-color: #d1d5db;
        background-color: #ffffff;
    }

    .form-textarea-large {
        min-height: 400px;
        font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-button {
        padding: 12px 24px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .form-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
    }

    .form-button:active {
        transform: translateY(0);
    }

    .form-button-primary {
        background-color: #10B981;
        color: white;
        border-color: #10B981;
    }

    .form-button-primary:hover {
        background-color: #059669;
        border-color: #059669;
    }

    .form-button-secondary {
        background-color: #6B7280;
        color: white;
        border-color: #6B7280;
    }

    .form-button-secondary:hover {
        background-color: #4B5563;
        border-color: #4B5563;
    }

    .form-file-input {
        padding: 8px;
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        background-color: #f9fafb;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .form-file-input:hover {
        border-color: #10B981;
        background-color: #f0fdf4;
    }

    .form-file-input:focus-within {
        border-color: #10B981;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .section-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }

    .section-header {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        padding: 16px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .section-content {
        padding: 24px;
    }

    .char-counter {
        font-size: 12px;
        color: #6b7280;
        text-align: right;
        margin-top: 4px;
    }

    .char-counter.warning {
        color: #f59e0b;
    }

    .char-counter.danger {
        color: #ef4444;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Créer un Article</h2>
            <p class="mt-1 text-sm text-gray-600">Créez un nouvel article de blog</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.blog.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-600 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations de base -->
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="text-lg font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Informations principales de l'article
                        </h3>
                        <p class="text-sm text-green-100 mt-1">Définissez le titre, le contenu et les détails essentiels</p>
                    </div>
                    <div class="section-content space-y-6">
                        <!-- Titre -->
                        <div class="space-y-2">
                            <label for="title" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Titre de l'article *
                            </label>
                            <input type="text" 
                                   name="title" 
                                   id="title"
                                   value="{{ old('title') }}"
                                   required
                                   maxlength="255"
                                   placeholder="Un titre accrocheur pour votre article..."
                                   class="form-input w-full @error('title') border-red-300 @enderror"
                                   onkeyup="updateCharCount('title', 255)">
                            <div class="char-counter" id="title-counter">0 / 255 caractères</div>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="space-y-2">
                            <label for="slug" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                URL personnalisée (Slug)
                            </label>
                            <input type="text" 
                                   name="slug" 
                                   id="slug"
                                   value="{{ old('slug') }}"
                                   maxlength="255"
                                   placeholder="url-de-votre-article"
                                   class="form-input w-full @error('slug') border-red-300 @enderror">
                            <p class="text-xs text-gray-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Laissez vide pour générer automatiquement à partir du titre
                            </p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Extrait -->
                        <div class="space-y-2">
                            <label for="excerpt" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                                </svg>
                                Résumé de l'article
                            </label>
                            <textarea name="excerpt" 
                                      id="excerpt"
                                      rows="4"
                                      maxlength="500"
                                      placeholder="Un résumé engageant qui donne envie de lire la suite..."
                                      class="form-textarea w-full @error('excerpt') border-red-300 @enderror"
                                      onkeyup="updateCharCount('excerpt', 500)">{{ old('excerpt') }}</textarea>
                            <div class="char-counter" id="excerpt-counter">0 / 500 caractères</div>
                            @error('excerpt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contenu -->
                        <div class="space-y-2">
                            <label for="content" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Contenu complet de l'article *
                            </label>
                            <div id="quill-editor" style="height: 400px;" class="border border-gray-300 rounded-lg bg-white"></div>
                            <textarea name="content" 
                                      id="content"
                                      required
                                      style="display: none;"
                                      class="@error('content') border-red-300 @enderror">{{ old('content') }}</textarea>
                            <p class="text-xs text-gray-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Utilisez l'éditeur riche pour formater votre contenu (gras, italique, listes, liens, images...)
                            </p>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="text-lg font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Optimisation SEO
                        </h3>
                        <p class="text-sm text-green-100 mt-1">Améliorez la visibilité de votre article sur les moteurs de recherche</p>
                    </div>
                    <div class="section-content space-y-6">
                        <!-- Meta Title -->
                        <div class="space-y-2">
                            <label for="meta_title" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Titre SEO
                            </label>
                            <input type="text" 
                                   name="meta_title" 
                                   id="meta_title"
                                   value="{{ old('meta_title') }}"
                                   maxlength="255"
                                   placeholder="Titre optimisé pour les moteurs de recherche..."
                                   class="form-input w-full"
                                   onkeyup="updateCharCount('meta_title', 255)">
                            <div class="char-counter" id="meta_title-counter">0 / 255 caractères</div>
                            <p class="text-xs text-gray-500">Ce titre apparaîtra dans les résultats de recherche Google</p>
                        </div>

                        <!-- Meta Description -->
                        <div class="space-y-2">
                            <label for="meta_description" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                                </svg>
                                Description SEO
                            </label>
                            <textarea name="meta_description" 
                                      id="meta_description"
                                      rows="4"
                                      maxlength="300"
                                      placeholder="Description engageante qui apparaîtra sous le titre dans Google..."
                                      class="form-textarea w-full"
                                      onkeyup="updateCharCount('meta_description', 300)">{{ old('meta_description') }}</textarea>
                            <div class="char-counter" id="meta_description-counter">0 / 300 caractères</div>
                            <p class="text-xs text-gray-500">Décrivez brièvement le contenu pour inciter au clic depuis Google</p>
                        </div>

                        <!-- Tags -->
                        <div class="space-y-2">
                            <label for="tags" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Tags et mots-clés
                            </label>
                            <input type="text" 
                                   name="tags" 
                                   id="tags"
                                   value="{{ old('tags') }}"
                                   placeholder="agriculture, jardinage, bio, légumes..."
                                   class="form-input w-full">
                            <p class="text-xs text-gray-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Séparez les mots-clés par des virgules pour améliorer la recherche
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publication -->
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="text-lg font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-4h5l-5-5-5 5h5v4z"/>
                            </svg>
                            Options de publication
                        </h3>
                    </div>
                    <div class="section-content space-y-6">
                        <!-- Catégorie -->
                        <div class="space-y-2">
                            <label for="blog_category_id" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                Catégorie *
                            </label>
                            <select name="blog_category_id" id="blog_category_id" required class="form-select w-full @error('blog_category_id') border-red-300 @enderror">
                                <option value="">Choisissez une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('blog_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('blog_category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Statut -->
                        <div class="space-y-2">
                            <label for="status" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Statut de publication *
                            </label>
                            <select name="status" id="status" required class="form-select w-full" onchange="toggleScheduledField()">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>✅ Publié immédiatement</option>
                                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>⏰ Publication programmée</option>
                            </select>
                        </div>

                        <!-- Date de programmation -->
                        <div id="scheduled_for_field" style="display: none;" class="space-y-2">
                            <label for="scheduled_for" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Date et heure de publication
                            </label>
                            <input type="datetime-local" 
                                   name="scheduled_for" 
                                   id="scheduled_for"
                                   value="{{ old('scheduled_for') }}"
                                   class="form-input w-full">
                            <p class="text-xs text-gray-500">L'article sera publié automatiquement à cette date</p>
                        </div>

                        <!-- Options avancées -->
                        <div class="space-y-4 pt-4 border-t border-gray-200">
                            <h4 class="form-label text-gray-700">Options avancées</h4>
                            
                            <div class="space-y-3">
                                <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-200">
                                    <input type="checkbox" 
                                           name="allow_comments" 
                                           id="allow_comments"
                                           value="1"
                                           {{ old('allow_comments', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <label for="allow_comments" class="ml-3 text-sm font-medium text-green-900 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.418 8-9.87 8a9.839 9.839 0 01-2.38-.298l-4.75 1.526V12c0-4.418 4.418-8 9.87-8s9.87 3.582 9.87 8z"/>
                                        </svg>
                                        Autoriser les commentaires
                                    </label>
                                </div>

                                <div class="flex items-center p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <input type="checkbox" 
                                           name="is_featured" 
                                           id="is_featured"
                                           value="1"
                                           {{ old('is_featured') ? 'checked' : '' }}
                                           class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded">
                                    <label for="is_featured" class="ml-3 text-sm font-medium text-yellow-900 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        Article en vedette
                                    </label>
                                </div>

                                <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <input type="checkbox" 
                                           name="is_sticky" 
                                           id="is_sticky"
                                           value="1"
                                           {{ old('is_sticky') ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_sticky" class="ml-3 text-sm font-medium text-blue-900 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                        </svg>
                                        Épingler en haut
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image mise en avant -->
                <div class="section-card">
                    <div class="section-header">
                        <h3 class="text-lg font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Image de couverture
                        </h3>
                        <p class="text-sm text-green-100 mt-1">Une image attrayante pour illustrer votre article</p>
                    </div>
                    <div class="section-content">
                        <div class="space-y-4">
                            <label for="featured_image" class="form-label">
                                <svg class="inline w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Sélectionner une image
                            </label>
                            
                            <div class="form-file-input">
                                <input type="file" 
                                       name="featured_image" 
                                       id="featured_image"
                                       accept="image/jpeg,image/png,image/jpg,image/webp"
                                       class="block w-full text-sm text-gray-600
                                              file:mr-4 file:py-3 file:px-6
                                              file:rounded-lg file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-green-50 file:text-green-700
                                              hover:file:bg-green-100 file:cursor-pointer
                                              focus:outline-none @error('featured_image') border-red-300 @enderror">
                                
                                <div class="mt-3 flex items-center text-xs text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Formats acceptés : JPG, PNG, WEBP • Taille max : 5MB • Résolution recommandée : 1200×630px
                                </div>
                            </div>
                            
                            @error('featured_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions de publication -->
                <div class="section-card">
                    <div class="section-content">
                        <div class="space-y-4">
                            <button type="submit" class="form-button form-button-primary w-full">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                Créer l'article
                            </button>
                            
                            <a href="{{ route('admin.blog.index') }}" class="form-button form-button-secondary w-full text-center block">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Annuler et retourner
                            </a>
                        </div>
                    </div>
                </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Génération automatique du slug
    const titleField = document.getElementById('title');
    const slugField = document.getElementById('slug');
    
    titleField.addEventListener('input', function() {
        if (!slugField.value || slugField.value === generateSlug(titleField.dataset.previousValue || '')) {
            const slug = generateSlug(this.value);
            slugField.value = slug;
        }
        titleField.dataset.previousValue = this.value;
    });

    function generateSlug(text) {
        return text
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    // Gestion du champ de programmation
    const statusField = document.getElementById('status');
    const scheduledField = document.getElementById('scheduled_for_field');
    
    function toggleScheduledField() {
        if (statusField.value === 'scheduled') {
            scheduledField.style.display = 'block';
            document.getElementById('scheduled_for').required = true;
        } else {
            scheduledField.style.display = 'none';
            document.getElementById('scheduled_for').required = false;
        }
    }
    
    statusField.addEventListener('change', toggleScheduledField);
    toggleScheduledField(); // Appel initial

    // Compteurs de caractères
    function updateCharCount(fieldId, maxLength) {
        const field = document.getElementById(fieldId);
        const counter = document.getElementById(fieldId + '-counter');
        if (field && counter) {
            const currentLength = field.value.length;
            counter.textContent = `${currentLength} / ${maxLength} caractères`;
            
            // Changer la couleur selon le pourcentage
            if (currentLength > maxLength * 0.9) {
                counter.className = 'char-counter danger';
            } else if (currentLength > maxLength * 0.75) {
                counter.className = 'char-counter warning';
            } else {
                counter.className = 'char-counter';
            }
        }
    }

    // Initialiser les compteurs
    ['title', 'excerpt', 'meta_title', 'meta_description'].forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            const maxLength = field.getAttribute('maxlength');
            if (maxLength) {
                updateCharCount(fieldId, parseInt(maxLength));
                field.addEventListener('input', () => updateCharCount(fieldId, parseInt(maxLength)));
            }
        }
    });

    // Prévisualisation d'image
    const imageInput = document.getElementById('featured_image');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                
                // Vérifier la taille
                if (file.size > 5 * 1024 * 1024) { // 5MB
                    alert('L\'image est trop volumineuse. Taille maximum : 5MB');
                    e.target.value = '';
                    return;
                }
                
                // Afficher les informations du fichier
                const fileInfo = document.createElement('div');
                fileInfo.className = 'mt-2 p-3 bg-green-50 border border-green-200 rounded-lg';
                fileInfo.innerHTML = `
                    <div class="flex items-center text-sm text-green-800">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Fichier sélectionné : <strong>${file.name}</strong> (${(file.size / 1024 / 1024).toFixed(2)} MB)
                    </div>
                `;
                
                // Supprimer l'ancien message s'il existe
                const existingInfo = imageInput.parentNode.querySelector('.mt-2');
                if (existingInfo && existingInfo !== fileInfo) {
                    existingInfo.remove();
                }
                
                imageInput.parentNode.appendChild(fileInfo);
            }
        });
    }
});

// Fonction globale pour les compteurs (appelée depuis onkeyup)
function updateCharCount(fieldId, maxLength) {
    const field = document.getElementById(fieldId);
    const counter = document.getElementById(fieldId + '-counter');
    if (field && counter) {
        const currentLength = field.value.length;
        counter.textContent = `${currentLength} / ${maxLength} caractères`;
        
        if (currentLength > maxLength * 0.9) {
            counter.className = 'char-counter danger';
        } else if (currentLength > maxLength * 0.75) {
            counter.className = 'char-counter warning';
        } else {
            counter.className = 'char-counter';
        }
    }
}

// Fonction pour le champ de programmation (appelée depuis onchange)
function toggleScheduledField() {
    const statusField = document.getElementById('status');
    const scheduledField = document.getElementById('scheduled_for_field');
    
    if (statusField.value === 'scheduled') {
        scheduledField.style.display = 'block';
        document.getElementById('scheduled_for').required = true;
    } else {
        scheduledField.style.display = 'none';
        document.getElementById('scheduled_for').required = false;
    }
}

// Configuration de Quill.js
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Quill
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                ['blockquote', 'code-block'],
                ['link', 'image'],
                ['clean']
            ]
        },
        placeholder: 'Rédigez ici le contenu complet de votre article avec l\'éditeur riche...',
        readOnly: false
    });

    // Charger le contenu existant si disponible
    var hiddenTextarea = document.getElementById('content');
    if (hiddenTextarea.value) {
        quill.root.innerHTML = hiddenTextarea.value;
    }

    // Synchroniser le contenu avec le textarea caché lors de la saisie
    quill.on('text-change', function() {
        hiddenTextarea.value = quill.root.innerHTML;
    });

    // S'assurer que le contenu est synchronisé avant soumission
    document.querySelector('form').addEventListener('submit', function() {
        hiddenTextarea.value = quill.root.innerHTML;
    });
});
</script>
@endpush
@endsection
