@php
    $maxLevel = 3; // Niveau maximum d'imbrication
@endphp

<div class="comment" id="comment-{{ $comment->id }}" style="margin-left: {{ min($level, $maxLevel) * 2 }}rem;">
    <div class="flex space-x-3">
        <!-- Avatar -->
        <div class="flex-shrink-0">
            @if($comment->user->avatar)
                <img src="{{ Storage::url($comment->user->avatar) }}" 
                     alt="{{ $comment->user->name }}"
                     class="w-10 h-10 rounded-full object-cover">
            @else
                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                    <span class="text-gray-600 font-medium text-sm">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </span>
                </div>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <!-- Header du commentaire -->
            <div class="flex items-center space-x-2 mb-2">
                <h4 class="text-sm font-medium text-gray-900">
                    {{ $comment->user->name }}
                </h4>
                <time class="text-xs text-gray-500" datetime="{{ $comment->created_at->format('Y-m-d\TH:i:s') }}">
                    {{ $comment->created_at->diffForHumans() }}
                </time>
                @if($comment->is_edited)
                    <span class="text-xs text-gray-400">(modifié)</span>
                @endif
            </div>

            <!-- Contenu du commentaire -->
            <div class="text-gray-700 text-sm leading-relaxed mb-3">
                {{ $comment->content }}
            </div>

            <!-- Actions du commentaire -->
            <div class="flex items-center space-x-4 text-xs">
                @auth
                    <!-- Répondre (seulement si pas au niveau max) -->
                    @if($level < $maxLevel)
                        <button onclick="toggleReplyForm({{ $comment->id }})" 
                                class="text-green-600 hover:text-green-700 font-medium">
                            Répondre
                        </button>
                    @endif

                    <!-- Signaler -->
                    <button onclick="reportComment({{ $comment->id }})" 
                            class="text-red-600 hover:text-red-700 font-medium">
                        Signaler
                    </button>

                    <!-- Modifier/Supprimer (si c'est l'auteur) -->
                    @if(auth()->user()->id === $comment->user_id)
                        <button onclick="editComment({{ $comment->id }})" 
                                class="text-blue-600 hover:text-blue-700 font-medium">
                            Modifier
                        </button>
                        <button onclick="deleteComment({{ $comment->id }})" 
                                class="text-red-600 hover:text-red-700 font-medium">
                            Supprimer
                        </button>
                    @endif
                @endauth

                <!-- Nombre de réponses -->
                @if($comment->approvedReplies && $comment->approvedReplies->count() > 0)
                    <span class="text-gray-500">
                        {{ $comment->approvedReplies->count() }} 
                        {{ $comment->approvedReplies->count() === 1 ? 'réponse' : 'réponses' }}
                    </span>
                @endif
            </div>

            <!-- Formulaire de réponse (masqué par défaut) -->
            @auth
                @if($level < $maxLevel)
                    <div id="reply-form-{{ $comment->id }}" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                        <form onsubmit="submitReply(event, {{ $comment->id }})" class="space-y-3">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <input type="hidden" name="blog_post_id" value="{{ $comment->blog_post_id }}">
                            <textarea name="content" 
                                      required
                                      rows="3" 
                                      placeholder="Votre réponse..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent resize-none text-sm"></textarea>
                            <div class="flex space-x-2">
                                <button type="submit" 
                                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                    Répondre
                                </button>
                                <button type="button" 
                                        onclick="toggleReplyForm({{ $comment->id }})"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            @endauth

            <!-- Formulaire d'édition (masqué par défaut) -->
            @auth
                @if(auth()->user()->id === $comment->user_id)
                    <div id="edit-form-{{ $comment->id }}" class="hidden mt-4 p-4 bg-blue-50 rounded-lg">
                        <form onsubmit="submitEdit(event, {{ $comment->id }})" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <textarea name="content" 
                                      required
                                      rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none text-sm">{{ $comment->content }}</textarea>
                            <div class="flex space-x-2">
                                <button type="submit" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    Modifier
                                </button>
                                <button type="button" 
                                        onclick="toggleEditForm({{ $comment->id }})"
                                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 text-sm">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            @endauth

            <!-- Réponses (récursif) -->
            @if($comment->approvedReplies && $comment->approvedReplies->count() > 0)
                <div class="mt-4 space-y-4">
                    @foreach($comment->approvedReplies as $reply)
                        @include('blog.partials.comment', ['comment' => $reply, 'level' => $level + 1])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Fonctions pour gérer les commentaires
function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    form.classList.toggle('hidden');
    
    if (!form.classList.contains('hidden')) {
        form.querySelector('textarea').focus();
    }
}

function toggleEditForm(commentId) {
    const form = document.getElementById(`edit-form-${commentId}`);
    form.classList.toggle('hidden');
    
    if (!form.classList.contains('hidden')) {
        form.querySelector('textarea').focus();
    }
}

async function submitReply(event, parentId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    submitButton.textContent = 'Envoi...';
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
            showNotification('Réponse publiée avec succès !', 'success');
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
}

async function submitEdit(event, commentId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    submitButton.textContent = 'Modification...';
    submitButton.disabled = true;
    
    try {
        const response = await fetch(`/api/blog/comments/${commentId}`, {
            method: 'POST', // Laravel va interpréter comme PATCH grâce à la directive method
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Commentaire modifié avec succès !', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification(data.message || 'Erreur lors de la modification', 'error');
        }
    } catch (error) {
        showNotification('Erreur de connexion', 'error');
    } finally {
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    }
}

async function deleteComment(commentId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/blog/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Commentaire supprimé avec succès !', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification(data.message || 'Erreur lors de la suppression', 'error');
        }
    } catch (error) {
        showNotification('Erreur de connexion', 'error');
    }
}

async function reportComment(commentId) {
    const reason = prompt('Motif du signalement (optionnel):');
    
    if (reason === null) return; // Utilisateur a annulé
    
    try {
        const response = await fetch('/api/blog/comment-reports', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                blog_comment_id: commentId,
                reason: reason || 'Contenu inapproprié'
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('Commentaire signalé avec succès !', 'success');
        } else {
            showNotification(data.message || 'Erreur lors du signalement', 'error');
        }
    } catch (error) {
        showNotification('Erreur de connexion', 'error');
    }
}
</script>
@endpush
