@extends('layouts.admin')

@section('title', 'Modifier l\'Article - FarmShop Admin')
@section('page-title', 'Modifier l\'Article')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">Modifier l'Article</h2>
            <p class="mt-1 text-sm text-gray-600">{{ $blogPost->title }}</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('blog.show', $blogPost->slug) }}" target="_blank"
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 active:bg-blue-600 disabled:opacity-25 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Voir l'article
            </a>
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
    <form method="POST" action="{{ route('admin.blog.update', $blogPost) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contenu principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informations de base -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informations de base</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Titre -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Titre *</label>
                            <input type="text" 
                                   name="title" 
                                   id="title"
                                   value="{{ old('title', $blogPost->title) }}"
                                   required
                                   maxlength="255"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('title') border-red-300 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" 
                                   name="slug" 
                                   id="slug"
                                   value="{{ old('slug', $blogPost->slug) }}"
                                   maxlength="255"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('slug') border-red-300 @enderror">
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Extrait -->
                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700">Extrait</label>
                            <textarea name="excerpt" 
                                      id="excerpt"
                                      rows="3"
                                      maxlength="500"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('excerpt') border-red-300 @enderror">{{ old('excerpt', $blogPost->excerpt) }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Résumé court de l'article (500 caractères max)</p>
                            @error('excerpt')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contenu -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700">Contenu *</label>
                            <div id="quill-editor" style="height: 400px;" class="mt-1 border border-gray-300 rounded-lg bg-white"></div>
                            <textarea name="content" 
                                      id="content"
                                      required
                                      style="display: none;"
                                      class="@error('content') border-red-300 @enderror">{{ old('content', $blogPost->content) }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">SEO et Métadonnées</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700">Titre SEO</label>
                            <input type="text" 
                                   name="meta_title" 
                                   id="meta_title"
                                   value="{{ old('meta_title', $blogPost->meta_title) }}"
                                   maxlength="255"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700">Description SEO</label>
                            <textarea name="meta_description" 
                                      id="meta_description"
                                      rows="3"
                                      maxlength="500"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">{{ old('meta_description', $blogPost->meta_description) }}</textarea>
                        </div>

                        <!-- Meta Keywords -->
                        <div>
                            <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Mots-clés SEO</label>
                            <input type="text" 
                                   name="meta_keywords" 
                                   id="meta_keywords"
                                   value="{{ old('meta_keywords', $blogPost->meta_keywords) }}"
                                   maxlength="255"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <p class="mt-1 text-xs text-gray-500">Séparez les mots-clés par des virgules</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Publication -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Publication</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Statut -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Statut *</label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="draft" {{ old('status', $blogPost->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                <option value="published" {{ old('status', $blogPost->status) == 'published' ? 'selected' : '' }}>Publié</option>
                                <option value="scheduled" {{ old('status', $blogPost->status) == 'scheduled' ? 'selected' : '' }}>Programmé</option>
                                <option value="archived" {{ old('status', $blogPost->status) == 'archived' ? 'selected' : '' }}>Archivé</option>
                            </select>
                        </div>

                        <!-- Date de programmation -->
                        <div id="scheduled_for_field" style="display: {{ old('status', $blogPost->status) == 'scheduled' ? 'block' : 'none' }};">
                            <label for="scheduled_for" class="block text-sm font-medium text-gray-700">Date de publication</label>
                            <input type="datetime-local" 
                                   name="scheduled_for" 
                                   id="scheduled_for"
                                   value="{{ old('scheduled_for', $blogPost->scheduled_for ? $blogPost->scheduled_for->format('Y-m-d\TH:i') : '') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>

                        <!-- Options -->
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="allow_comments" 
                                       id="allow_comments"
                                       value="1"
                                       {{ old('allow_comments', $blogPost->allow_comments) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <label for="allow_comments" class="ml-2 text-sm text-gray-700">Autoriser les commentaires</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_featured" 
                                       id="is_featured"
                                       value="1"
                                       {{ old('is_featured', $blogPost->is_featured) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <label for="is_featured" class="ml-2 text-sm text-gray-700">Mettre en avant</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_sticky" 
                                       id="is_sticky"
                                       value="1"
                                       {{ old('is_sticky', $blogPost->is_sticky) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <label for="is_sticky" class="ml-2 text-sm text-gray-700">Épingler en haut</label>
                            </div>
                        </div>

                        <!-- Informations d'édition -->
                        @if($blogPost->is_edited)
                            <div class="text-xs text-gray-500 p-3 bg-gray-50 rounded">
                                Article modifié le {{ $blogPost->updated_at->format('d/m/Y à H:i') }}
                                @if($blogPost->lastEditor)
                                    par {{ $blogPost->lastEditor->name }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Catégorie -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Catégorie</h3>
                    </div>
                    <div class="p-6">
                        <select name="blog_category_id" id="blog_category_id" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">Sélectionner une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('blog_category_id', $blogPost->blog_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('blog_category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tags -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Tags</h3>
                    </div>
                    <div class="p-6">
                        <input type="text" 
                               name="tags" 
                               id="tags"
                               value="{{ old('tags', is_array($blogPost->tags) ? implode(', ', $blogPost->tags) : $blogPost->tags) }}"
                               placeholder="Séparés par des virgules"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <p class="mt-1 text-xs text-gray-500">Ex: agriculture, innovation, technologie</p>
                    </div>
                </div>

                <!-- Image à la une -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Image à la une</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($blogPost->featured_image)
                            <div>
                                <img src="{{ Storage::url($blogPost->featured_image) }}" alt="{{ $blogPost->title }}" class="w-full h-32 object-cover rounded-lg">
                                <p class="mt-1 text-xs text-gray-500">Image actuelle</p>
                            </div>
                        @endif
                        
                        <input type="file" 
                               name="featured_image" 
                               id="featured_image"
                               accept="image/jpeg,image/png,image/jpg,image/webp"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                        <p class="mt-1 text-xs text-gray-500">
                            JPG, PNG, WEBP. Max 5MB.
                            @if($blogPost->featured_image)
                                Laissez vide pour conserver l'image actuelle.
                            @endif
                        </p>
                        @error('featured_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Statistiques</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Vues:</span>
                            <span class="font-medium">{{ number_format($blogPost->views_count ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Commentaires:</span>
                            <span class="font-medium">{{ number_format($blogPost->comments_count ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Créé le:</span>
                            <span class="font-medium">{{ $blogPost->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($blogPost->published_at)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Publié le:</span>
                                <span class="font-medium">{{ $blogPost->published_at->format('d/m/Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col space-y-3">
                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Mettre à jour
                            </button>
                            <a href="{{ route('admin.blog.index') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Annuler
                            </a>
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
    
    // Configuration de Quill.js
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

    // Charger le contenu existant
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
