@extends('layouts.admin')

@section('title', 'Gestion des Articles de Blog - FarmShop Admin')
@section('page-title', 'Gestion des Articles de Blog')

@push('styles')
<style>
    .blog-card {
        transition: all 0.3s ease;
    }
    .blog-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .status-badge {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
</style>
@endpush

@section('content')
<div x-data="blogManager" class="space-y-6">
    <!-- En-tête amélioré -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                    <svg class="h-8 w-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    Articles de Blog
                </h1>
                <p class="mt-2 text-sm text-gray-600">Gérez et consultez tous les articles de votre blog FarmShop</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:flex sm:space-x-3">
                <a href="{{ route('admin.blog-categories.index') }}" class="inline-flex items-center px-4 py-2 border border-purple-300 rounded-lg shadow-sm text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Gérer Catégories
                </a>
                <a href="/blog" target="_blank" class="inline-flex items-center px-4 py-2 border border-green-300 rounded-lg shadow-sm text-sm font-medium text-green-700 bg-green-50 hover:bg-green-100 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Voir le Blog Public
                </a>
                <a href="{{ route('admin.blog.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvel Article
                </a>
            </div>
        </div>
    </div>
    <!-- Statistiques améliorées -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200 hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Articles</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $posts->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200 hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Publiés</dt>
                            <dd class="text-2xl font-bold text-green-600">{{ $posts->filter(function($post) { return $post->status === 'published'; })->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200 hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.348 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Brouillons</dt>
                            <dd class="text-2xl font-bold text-yellow-600">{{ $posts->filter(function($post) { return $post->status === 'draft'; })->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200 hover:shadow-xl transition-shadow">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Catégories</dt>
                            <dd class="text-2xl font-bold text-blue-600">{{ $categories->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche améliorés -->
    <div class="bg-white shadow-lg rounded-xl border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-blue-50 rounded-t-xl">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="h-5 w-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Recherche et Filtres Avancés
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.blog.index') }}" x-data="{ showAdvanced: false }">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
                    <!-- Recherche améliorée -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Recherche d'articles
                        </label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}" 
                               placeholder="Titre, contenu, extrait, tags..." 
                               class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm">
                    </div>

                    <!-- Catégorie -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Catégorie
                        </label>
                        <select name="category_id" id="category_id" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }} ({{ $category->posts->count() }} articles)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Statut avec icônes -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Statut de publication
                        </label>
                        <select name="status" id="status" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm">
                            <option value="">Tous les statuts</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>✅ Publié</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>📝 Brouillon</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>⏰ Programmé</option>
                        </select>
                    </div>
                </div>

                <!-- Filtres avancés -->
                <div x-show="showAdvanced" x-transition class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3 border-t border-gray-200 pt-6">
                    <!-- Auteur -->
                    <div>
                        <label for="author_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Auteur
                        </label>
                        <select name="author_id" id="author_id" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm">
                            <option value="">Tous les auteurs</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ request('author_id') == $author->id ? 'selected' : '' }}>
                                    {{ $author->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tri -->
                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/>
                            </svg>
                            Trier par
                        </label>
                        <select name="sort_by" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date de création</option>
                            <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>Dernière modification</option>
                            <option value="published_at" {{ request('sort_by') == 'published_at' ? 'selected' : '' }}>Date de publication</option>
                            <option value="views_count" {{ request('sort_by') == 'views_count' ? 'selected' : '' }}>Nombre de vues</option>
                            <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Titre alphabétique</option>
                        </select>
                    </div>

                    <!-- Ordre -->
                    <div>
                        <label for="sort_direction" class="block text-sm font-medium text-gray-700 mb-2">
                            <svg class="inline h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                            </svg>
                            Ordre
                        </label>
                        <select name="sort_direction" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 text-sm">
                            <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>↓ Décroissant</option>
                            <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>↑ Croissant</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-3 sm:space-y-0">
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                @click="showAdvanced = !showAdvanced" 
                                class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                            <span x-text="showAdvanced ? 'Masquer filtres avancés' : 'Filtres avancés'"></span>
                        </button>
                        <a href="{{ route('admin.blog.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Réinitialiser
                        </a>
                    </div>
                    <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des articles -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Articles ({{ $posts->total() }})</h3>
                <div class="flex space-x-2">
                    <span class="text-sm text-gray-500">
                        Tri par {{ request('sort_by', 'created_at') }} ({{ request('sort_direction', 'desc') }})
                    </span>
                </div>
            </div>
        </div>

        @if($posts->count() > 0)
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Article
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Catégorie
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Auteur
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vues
                            </th>
                            <th class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($posts as $post)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($post->featured_image)
                                            <img class="h-10 w-10 rounded-lg object-cover mr-4" src="{{ Storage::url($post->featured_image) }}" alt="">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-200 flex items-center justify-center mr-4">
                                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ Str::limit($post->title, 50) }}
                                                @if($post->is_featured)
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        ★ Mis en avant
                                                    </span>
                                                @endif
                                            </div>
                                            @if($post->excerpt)
                                                <div class="text-sm text-gray-500">{{ Str::limit($post->excerpt, 80) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($post->category)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                              style="background-color: {{ $post->category->color ?? '#e5e7eb' }}20; color: {{ $post->category->color ?? '#6b7280' }}">
                                            {{ $post->category->name }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">Sans catégorie</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $post->author->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $post->author->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($post->status)
                                        @case('published')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Publié
                                            </span>
                                            @break
                                        @case('draft')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Brouillon
                                            </span>
                                            @break
                                        @case('scheduled')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Programmé
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $post->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs">{{ $post->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($post->views_count ?? 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        @if($post->status === 'published')
                                            <a href="/blog/{{ $post->slug }}" target="_blank" 
                                               class="text-blue-600 hover:text-blue-900 p-1 rounded-full hover:bg-blue-50 transition-colors" 
                                               title="Voir l'article">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        
                                        <a href="{{ route('admin.blog.edit', $post) }}" 
                                           class="text-green-600 hover:text-green-900 p-1 rounded-full hover:bg-green-50 transition-colors" 
                                           title="Modifier l'article">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        
                                        <button @click="deletePost({{ $post->id }}, '{{ addslashes($post->title) }}')" 
                                                class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-50 transition-colors" 
                                                title="Supprimer l'article">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200">
                {{ $posts->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun article</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->hasAny(['search', 'category_id', 'status', 'author_id']))
                        Aucun article ne correspond à vos critères de recherche.
                    @else
                        Commencez par créer votre premier article de blog.
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Note d'information améliorée -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-lg font-medium text-blue-900">💡 Information sur la gestion des articles</h3>
                <div class="mt-2 text-sm text-blue-800">
                    <p class="mb-2">Cette interface permet la <strong>consultation et le filtrage</strong> des articles existants.</p>
                    <p>La gestion complète (création, édition, suppression) est disponible via <strong>l'API REST</strong>. Consultez la documentation API pour plus de détails.</p>
                </div>
                <div class="mt-4 flex space-x-3">
                    <a href="#" class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors">
                        📖 Documentation API
                    </a>
                    <a href="/blog" target="_blank" class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors">
                        🌐 Voir le blog public
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal de confirmation de suppression -->
    <div x-show="showDeleteModal" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="display: none;">
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="cancelDelete()"></div>
        
        <!-- Modal Content -->
        <div x-transition:enter="transition ease-out duration-300" 
             x-transition:enter-start="opacity-0 transform scale-95" 
             x-transition:enter-end="opacity-100 transform scale-100" 
             x-transition:leave="transition ease-in duration-200" 
             x-transition:leave-start="opacity-100 transform scale-100" 
             x-transition:leave-end="opacity-0 transform scale-95"
             class="relative bg-white rounded-lg shadow-xl max-w-md w-full z-10"
             @click.stop>
            
            <!-- En-tête -->
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">
                    Confirmer la suppression
                </h3>
            </div>
            
            <!-- Contenu -->
            <div class="px-6 py-4">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700">
                            Êtes-vous sûr de vouloir supprimer l'article <strong x-text="deletePostData.title"></strong> ?
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            Cette action est irréversible.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t flex justify-end space-x-3">
                <button @click="cancelDelete()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                    Annuler
                </button>
                <button @click="confirmDelete()" 
                        :disabled="loading"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span x-show="!loading">Supprimer</span>
                    <span x-show="loading" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Suppression...
                    </span>
                </button>
            </div>
        </div>
    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('blogManager', () => ({
        loading: false,
        showDeleteModal: false,
        deletePostData: {
            id: null,
            title: ''
        },
        
        init() {
            // Initialisation du gestionnaire de blog
            console.log('Blog Manager initialized');
        },
        
        // Fonction pour ouvrir la modal de suppression
        deletePost(id, title) {
            console.log('deletePost called with:', id, title);
            this.deletePostData = { id, title };
            this.showDeleteModal = true;
        },
        
        // Fonction pour confirmer la suppression
        async confirmDelete() {
            if (!this.deletePostData.id) return;
            
            this.loading = true;
            
            try {
                const response = await fetch(`/admin/blog/${this.deletePostData.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                // Vérifier le statut de la réponse
                if (response.ok) {
                    // Essayer de parser la réponse JSON
                    let data;
                    try {
                        data = await response.json();
                    } catch (jsonError) {
                        // Si ce n'est pas du JSON valide, considérer comme succès
                        console.log('Réponse non-JSON, mais suppression réussie');
                        data = { success: true, message: 'Article supprimé avec succès!' };
                    }
                    
                    // Fermer la modal immédiatement
                    this.showDeleteModal = false;
                    
                    if (data.success !== false) {
                        this.showNotification(data.message || 'Article supprimé avec succès!', 'success');
                        // Recharger la page après 1.5 secondes pour voir la notification
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        throw new Error(data.message || 'Erreur lors de la suppression');
                    }
                } else {
                    // Gérer les erreurs HTTP
                    let errorMessage = 'Erreur lors de la suppression de l\'article';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorMessage;
                    } catch (e) {
                        errorMessage = `Erreur ${response.status}: ${response.statusText}`;
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Erreur complète:', error);
                this.showNotification(error.message || 'Erreur lors de la suppression de l\'article', 'error');
                // Ne pas fermer la modal en cas d'erreur pour permettre de réessayer
            } finally {
                this.loading = false;
            }
        },
        
        // Fermer la modal de suppression
        cancelDelete() {
            this.showDeleteModal = false;
            this.deletePostData = { id: null, title: '' };
        },
        
        // Fonction pour prévisualiser un article
        previewArticle(slug) {
            window.open(`/blog/${slug}`, '_blank');
        },
        
        // Fonction pour copier le lien d'un article
        copyArticleLink(slug) {
            const url = `${window.location.origin}/blog/${slug}`;
            navigator.clipboard.writeText(url).then(() => {
                this.showNotification('Lien copié dans le presse-papiers!', 'success');
            });
        },
        
        // Fonction utilitaire pour afficher les notifications
        showNotification(message, type = 'success') {
            // Créer un élément de notification
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            // Animation d'entrée
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
                notification.style.opacity = '1';
            }, 100);
            
            // Supprimer après 3 secondes
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    }));
});
</script>
@endpush
@endsection
