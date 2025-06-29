@extends('admin.layout')

@section('title', 'Message de ' . $adminMessage->user->name)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.messages.index') }}">Messages</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $adminMessage->subject }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-envelope text-primary me-2"></i>
                {{ $adminMessage->subject }}
            </h1>
        </div>
        <div class="d-flex gap-2">
            @if($adminMessage->status === 'pending')
                <span class="badge bg-warning">En attente</span>
            @elseif($adminMessage->status === 'in_progress')
                <span class="badge bg-info">En cours</span>
            @else
                <span class="badge bg-success">
                    <i class="fas fa-check me-1"></i>Traité
                </span>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Message principal -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <img src="{{ $adminMessage->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($adminMessage->user->name) }}" 
                             class="rounded-circle me-3" width="40" height="40">
                        <div>
                            <h6 class="m-0 font-weight-bold text-primary">{{ $adminMessage->user->name }}</h6>
                            <small class="text-muted">{{ $adminMessage->user->email }}</small>
                        </div>
                    </div>
                    <small class="text-muted">{{ $adminMessage->created_at->format('d/m/Y à H:i') }}</small>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $adminMessage->message }}</p>
                </div>
            </div>

            <!-- Fil de discussion -->
            @if($adminMessage->replies->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-comments me-2"></i>
                            Fil de discussion ({{ $adminMessage->replies->count() }} réponse{{ $adminMessage->replies->count() > 1 ? 's' : '' }})
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach($adminMessage->replies as $reply)
                            <div class="d-flex mb-3 {{ $reply->is_admin_reply ? 'justify-content-end' : '' }}">
                                <div class="message-bubble {{ $reply->is_admin_reply ? 'admin-reply' : 'user-reply' }}">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="{{ $reply->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) }}" 
                                             class="rounded-circle me-2" width="25" height="25">
                                        <strong class="me-2">{{ $reply->user->name }}</strong>
                                        @if($reply->is_admin_reply)
                                            <span class="badge bg-primary badge-sm">Admin</span>
                                        @endif
                                        <small class="text-muted ms-auto">{{ $reply->created_at->format('d/m/Y à H:i') }}</small>
                                    </div>
                                    <p class="mb-0">{{ $reply->message }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Formulaire de réponse -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-reply me-2"></i>
                        Répondre
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.messages.reply', $adminMessage) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="message" class="form-label">Votre réponse</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="4" 
                                      placeholder="Tapez votre réponse..." required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Retour à la liste
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i>
                                Envoyer la réponse
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Informations du message -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informations</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Statut :</strong>
                        @if($adminMessage->status === 'pending')
                            <span class="badge bg-warning">En attente</span>
                        @elseif($adminMessage->status === 'in_progress')
                            <span class="badge bg-info">En cours</span>
                        @else
                            <span class="badge bg-success">Traité</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>Date de création :</strong><br>
                        {{ $adminMessage->created_at->format('d/m/Y à H:i') }}
                    </div>
                    @if($adminMessage->read_at)
                        <div class="mb-3">
                            <strong>Lu le :</strong><br>
                            {{ $adminMessage->read_at->format('d/m/Y à H:i') }}
                        </div>
                    @endif
                    @if($adminMessage->resolved_at)
                        <div class="mb-3">
                            <strong>Résolu le :</strong><br>
                            {{ $adminMessage->resolved_at->format('d/m/Y à H:i') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    @if($adminMessage->status !== 'resolved')
                        <form method="POST" action="{{ route('admin.messages.resolve', $adminMessage) }}" class="mb-2">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm w-100">
                                <i class="fas fa-check me-1"></i>
                                Marquer comme résolu
                            </button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('admin.messages.destroy', $adminMessage) }}" 
                          onsubmit="return confirm('Supprimer ce message et toutes ses réponses ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="fas fa-trash me-1"></i>
                            Supprimer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.message-bubble {
    max-width: 80%;
    padding: 15px;
    border-radius: 15px;
    margin-bottom: 10px;
}

.user-reply {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.admin-reply {
    background-color: #e3f2fd;
    border: 1px solid #bbdefb;
}

.badge-sm {
    font-size: 0.7em;
}
</style>
@endsection
