@extends('admin.layout')

@section('title', 'Messages Utilisateurs')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-envelope text-primary me-2"></i>
            Messages Utilisateurs
        </h1>
        <div class="d-flex gap-2">
            <span class="badge bg-warning">{{ $messages->where('status', 'pending')->count() }} en attente</span>
            <span class="badge bg-success">{{ $messages->where('status', 'resolved')->count() }} résolus</span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des messages</h6>
        </div>
        <div class="card-body">
            @if($messages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Sujet</th>
                                <th>Statut</th>
                                <th>Date</th>
                                <th>Réponses</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                                <tr class="{{ !$message->read_at ? 'table-warning' : '' }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $message->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) }}" 
                                                 class="rounded-circle me-2" width="30" height="30">
                                            <div>
                                                <strong>{{ $message->user->name }}</strong>
                                                <small class="text-muted d-block">{{ $message->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $message->subject }}</strong>
                                            @if(!$message->read_at)
                                                <span class="badge bg-info ms-1">Nouveau</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ Str::limit($message->message, 60) }}</small>
                                    </td>
                                    <td>
                                        @if($message->status === 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @elseif($message->status === 'in_progress')
                                            <span class="badge bg-info">En cours</span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Traité
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>{{ $message->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $message->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $message->replies->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.messages.show', $message) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($message->status !== 'resolved')
                                                <form method="POST" action="{{ route('admin.messages.resolve', $message) }}" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-success" 
                                                            title="Marquer comme résolu">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" 
                                                  class="d-inline" onsubmit="return confirm('Supprimer ce message ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5>Aucun message</h5>
                    <p class="text-muted">Les messages des utilisateurs apparaîtront ici.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "pageLength": 15,
        "order": [[ 3, "desc" ]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/French.json"
        }
    });
});
</script>
@endsection
