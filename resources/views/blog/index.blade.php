@extends('layouts.app')

@section('title', 'Blog - FarmShop')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header du Blog -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Blog FarmShop</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Découvrez nos conseils d'experts, actualités agricoles et guides pratiques 
                    pour réussir vos cultures et optimiser vos pratiques agricoles.
                </p>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Contenu principal -->
            <div class="flex-1">
                <!-- Barre de recherche et filtres -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <form method="GET" action="{{ route('blog.index') }}" class="space-y-4">
                        <div class="flex flex-col md:flex-row gap-4">
                            <!-- Recherche -->
                            <div class="flex-1">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Rechercher un article..." 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            
                            <!-- Catégorie -->
                            <div class="w-full md:w-48">
                                <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Tri -->
                            <div class="w-full md:w-48">
                                <select name="sort_by" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="recent" {{ request('sort_by') == 'recent' ? 'selected' : '' }}>Plus récents</option>
                                    <option value="popular" {{ request('sort_by') == 'popular' ? 'selected' : '' }}>Plus populaires</option>
                                    <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Plus vus</option>
                                    <option value="comments" {{ request('sort_by') == 'comments' ? 'selected' : '' }}>Plus commentés</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Filtrer
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Articles -->
                @if($posts->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($posts as $post)
                            <article class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg hover:scale-[1.02] transition-all duration-300 group">
                                <a href="{{ route('blog.show', $post->slug) }}" class="block">
                                    <!-- Image -->
                                    @if($post->featured_image)
                                        <div class="aspect-video bg-gray-200 overflow-hidden">
                                            <img src="{{ Storage::url($post->featured_image) }}" 
                                                 alt="{{ $post->title }}"
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        </div>
                                    @else
                                        <div class="aspect-video bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-white opacity-50 group-hover:opacity-70 transition-opacity duration-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                            </svg>
                                        </div>
                                    @endif

                                    <div class="p-6">
                                        <!-- Catégorie et date -->
                                        <div class="flex items-center justify-between text-sm text-gray-500 mb-3">
                                            @if($post->category)
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                                                    {{ $post->category->name }}
                                                </span>
                                            @endif
                                            <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                                                {{ $post->published_at->format('d M Y') }}
                                            </time>
                                        </div>

                                        <!-- Titre -->
                                        <h2 class="text-xl font-semibold text-gray-900 mb-3 line-clamp-2 group-hover:text-green-600 transition-colors">
                                            {{ $post->title }}
                                        </h2>

                                        <!-- Extrait -->
                                        @if($post->excerpt)
                                            <p class="text-gray-600 mb-4 line-clamp-3">{{ $post->excerpt }}</p>
                                        @endif

                                        <!-- Métadonnées -->
                                        <div class="flex items-center justify-between text-sm text-gray-500">
                                            <div class="flex items-center space-x-4">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z"/>
                                                    </svg>
                                                    {{ $post->views_count }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                                    </svg>
                                                    {{ $post->likes_count }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                        <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.678 3.348-3.97z"/>
                                                    </svg>
                                                    {{ $post->comments_count }}
                                                </span>
                                            </div>
                                            @if($post->reading_time)
                                                <span>{{ $post->reading_time }} min de lecture</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white rounded-lg shadow-sm p-4">
                        {{ $posts->appends(request()->query())->links() }}
                    </div>
                @else
                    <!-- Aucun article -->
                    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun article trouvé</h3>
                        <p class="text-gray-600">
                            @if(request()->hasAny(['search', 'category', 'tag']))
                                Aucun article ne correspond à vos critères de recherche.
                                <a href="{{ route('blog.index') }}" class="text-green-600 hover:text-green-700">
                                    Voir tous les articles
                                </a>
                            @else
                                Les articles de blog seront bientôt disponibles.
                            @endif
                        </p>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="w-full lg:w-80">
                <!-- Articles populaires -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Articles populaires</h3>
                    @php
                        $popularPosts = \App\Models\BlogPost::published()
                            ->orderBy('views_count', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @if($popularPosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($popularPosts as $popularPost)
                                <div class="flex space-x-3">
                                    @if($popularPost->featured_image)
                                        <img src="{{ Storage::url($popularPost->featured_image) }}" 
                                             alt="{{ $popularPost->title }}"
                                             class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex-shrink-0"></div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 line-clamp-2 mb-1">
                                            <a href="{{ route('blog.show', $popularPost->slug) }}" class="hover:text-green-600">
                                                {{ $popularPost->title }}
                                            </a>
                                        </h4>
                                        <p class="text-xs text-gray-500">
                                            {{ $popularPost->published_at->format('d M Y') }} • {{ $popularPost->views_count }} vues
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-sm">Aucun article populaire pour le moment.</p>
                    @endif
                </div>

                <!-- Catégories -->
                @if($categories->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Catégories</h3>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                                   class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors">
                                    <span class="text-gray-700">{{ $category->name }}</span>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        {{ $category->posts->count() }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endpush
@endsection
