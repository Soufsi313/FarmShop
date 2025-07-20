@extends('layouts.app')

@section('title', $post->title . ' - Blog FarmShop')
@section('meta_description', $post->meta_description ?: $post->excerpt)

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-4">
            <nav class="flex text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-green-600">Accueil</a>
                <span class="mx-2">/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-green-600">Blog</a>
                <span class="mx-2">/</span>
                @if($post->category)
                    <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="hover:text-green-600">
                        {{ $post->category->name }}
                    </a>
                    <span class="mx-2">/</span>
                @endif
                <span class="text-gray-900">{{ Str::limit($post->title, 50) }}</span>
            </nav>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Article principal -->
            <article class="flex-1">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <!-- Image de couverture -->
                    @if($post->featured_image)
                        <div class="aspect-video bg-gray-200">
                            <img src="{{ Storage::url($post->featured_image) }}" 
                                 alt="{{ $post->title }}"
                                 class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="p-8">
                        <!-- Header de l'article -->
                        <header class="mb-8">
                            <!-- Catégorie et métadonnées -->
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 mb-4">
                                @if($post->category)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-medium">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <time datetime="{{ $post->published_at->format('Y-m-d') }}">
                                    {{ $post->published_at->format('d F Y') }}
                                </time>
                                @if($post->author)
                                    <span>Par {{ $post->author->name }}</span>
                                @endif
                                @if($post->reading_time)
                                    <span>{{ $post->reading_time }} min de lecture</span>
                                @endif
                            </div>

                            <!-- Titre -->
                            <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

                            <!-- Extrait -->
                            @if($post->excerpt)
                                <p class="text-xl text-gray-600 leading-relaxed">{{ $post->excerpt }}</p>
                            @endif

                            <!-- Statistiques et actions -->
                            <div class="flex flex-wrap items-center justify-between mt-6 pt-6 border-t border-gray-200">
                                <div class="flex items-center space-x-6 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z"/>
                                        </svg>
                                        {{ $post->views_count }} vues
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.678 3.348-3.97z"/>
                                        </svg>
                                        {{ $post->comments_count }} commentaires
                                    </span>
                                </div>

                                <!-- Boutons de partage -->
                                <div class="flex items-center space-x-2">
                                    <button onclick="shareOnFacebook()" 
                                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-full transition-colors"
                                            title="Partager sur Facebook">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </button>
                                    <button onclick="shareOnTwitter()" 
                                            class="p-2 text-blue-400 hover:bg-blue-50 rounded-full transition-colors"
                                            title="Partager sur Twitter">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </button>
                                    <button onclick="copyLink()" 
                                            class="p-2 text-gray-600 hover:bg-gray-50 rounded-full transition-colors"
                                            title="Copier le lien">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </header>

                        <!-- Contenu de l'article -->
                        <div class="prose prose-lg max-w-none">
                            {!! $post->content !!}
                        </div>

                        <!-- Tags -->
                        @if($post->tags && count($post->tags) > 0)
                            <div class="mt-8 pt-8 border-t border-gray-200">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Tags</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($post->tags as $tag)
                                        <a href="{{ route('blog.index', ['tag' => $tag]) }}" 
                                           class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm transition-colors">
                                            #{{ $tag }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Section commentaires -->
                <div class="mt-8 bg-white rounded-lg shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        Commentaires ({{ $comments->total() }})
                    </h2>

                    @auth
                        <!-- Formulaire de commentaire -->
                        <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Laisser un commentaire</h3>
                            <form id="comment-form" class="space-y-4">
                                @csrf
                                <input type="hidden" name="blog_post_id" value="{{ $post->id }}">
                                <div>
                                    <textarea name="content" 
                                              required
                                              rows="4" 
                                              placeholder="Votre commentaire..."
                                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none"></textarea>
                                </div>
                                <button type="submit" 
                                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                    Publier le commentaire
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="mb-8 p-6 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-blue-800">
                                <a href="{{ route('login') }}" class="font-medium hover:underline">Connectez-vous</a> 
                                ou 
                                <a href="{{ route('register') }}" class="font-medium hover:underline">inscrivez-vous</a> 
                                pour laisser un commentaire.
                            </p>
                        </div>
                    @endauth

                    <!-- Liste des commentaires -->
                    @if($comments->count() > 0)
                        <div class="space-y-6" id="comments-list">
                            @foreach($comments as $comment)
                                @include('blog.partials.comment', ['comment' => $comment, 'level' => 0])
                            @endforeach
                        </div>

                        <!-- Pagination des commentaires -->
                        <div class="mt-8">
                            {{ $comments->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            <p>Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
                        </div>
                    @endif
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="w-full lg:w-80">
                <!-- Navigation de l'article -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 sticky top-4">
                    <div class="flex justify-between mb-4">
                        @if($previousPost = \App\Models\BlogPost::published()->where('published_at', '<', $post->published_at)->latest('published_at')->first())
                            <a href="{{ route('blog.show', $previousPost->slug) }}" 
                               class="flex items-center text-sm text-green-600 hover:text-green-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Article précédent
                            </a>
                        @endif

                        @if($nextPost = \App\Models\BlogPost::published()->where('published_at', '>', $post->published_at)->oldest('published_at')->first())
                            <a href="{{ route('blog.show', $nextPost->slug) }}" 
                               class="flex items-center text-sm text-green-600 hover:text-green-700 ml-auto">
                                Article suivant
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                    
                    <a href="{{ route('blog.index') }}" 
                       class="block w-full text-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                        ← Retour au blog
                    </a>
                </div>

                <!-- Articles similaires -->
                @if($relatedPosts->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Articles similaires</h3>
                        <div class="space-y-4">
                            @foreach($relatedPosts as $relatedPost)
                                <div class="flex space-x-3">
                                    @if($relatedPost->featured_image)
                                        <img src="{{ Storage::url($relatedPost->featured_image) }}" 
                                             alt="{{ $relatedPost->title }}"
                                             class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex-shrink-0"></div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 line-clamp-2 mb-1">
                                            <a href="{{ route('blog.show', $relatedPost->slug) }}" 
                                               class="hover:text-green-600">
                                                {{ $relatedPost->title }}
                                            </a>
                                        </h4>
                                        <p class="text-xs text-gray-500">
                                            {{ $relatedPost->published_at->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</div>

<!-- Scripts pour les commentaires et partage -->
@push('scripts')
<script>
// Gestion du formulaire de commentaire
document.getElementById('comment-form')?.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    submitButton.textContent = 'Publication...';
    submitButton.disabled = true;
    
    try {
        const response = await fetch('/api/blog/comments', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Réinitialiser le formulaire
            this.reset();
            // Afficher un message de succès
            showNotification('Commentaire publié avec succès !', 'success');
            // Recharger la page pour afficher le nouveau commentaire
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification(data.message || 'Erreur lors de la publication', 'error');
        }
    } catch (error) {
        showNotification('Erreur de connexion', 'error');
    } finally {
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    }
});

// Fonctions de partage
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
}

function shareOnTwitter() {
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('{{ $post->title }}');
    window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        showNotification('Lien copié !', 'success');
    });
}

// Fonction de notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-md shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' :
        type === 'error' ? 'bg-red-100 text-red-800 border border-red-200' :
        'bg-blue-100 text-blue-800 border border-blue-200'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush

@push('styles')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose {
    color: #374151;
    line-height: 1.75;
}

.prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
    color: #1f2937;
    font-weight: 600;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.prose h2 {
    font-size: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 0.5rem;
}

.prose h3 {
    font-size: 1.25rem;
}

.prose p {
    margin-bottom: 1.25rem;
}

.prose a {
    color: #059669;
    text-decoration: underline;
}

.prose a:hover {
    color: #047857;
}

.prose img {
    border-radius: 0.5rem;
    margin: 1.5rem 0;
}

.prose blockquote {
    border-left: 4px solid #059669;
    background-color: #f0fdf4;
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    font-style: italic;
}

.prose code {
    background-color: #f3f4f6;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.prose pre {
    background-color: #1f2937;
    color: #f9fafb;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.prose ul, .prose ol {
    padding-left: 1.5rem;
    margin-bottom: 1.25rem;
}

.prose li {
    margin-bottom: 0.5rem;
}
</style>
@endpush
@endsection
