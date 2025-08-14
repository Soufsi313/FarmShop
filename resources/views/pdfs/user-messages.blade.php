<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Messages et Communications - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .message {
            border: 1px solid #ddd;
            margin-bottom: 15px;
            padding: 15px;
        }
        .message-header {
            background-color: #f8f9fa;
            padding: 10px;
            margin: -15px -15px 15px -15px;
            border-bottom: 1px solid #ddd;
        }
        .message-info {
            display: flex;
            margin-bottom: 10px;
        }
        .message-label {
            font-weight: bold;
            width: 120px;
        }
        .message-content {
            background-color: #f8f9fa;
            padding: 10px;
            margin-top: 10px;
            border-left: 3px solid #27ae60;
        }
        .message-meta {
            font-size: 10px;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Messages et Communications</h1>
        <h2>{{ $user->name }}</h2>
        <p>Exporté le {{ now()->format('d/m/Y à H:i:s') }}</p>
    </div>

    @forelse($messages as $message)
        <div class="message">
            <div class="message-header">
                <h3>Message #{{ $message->id }}</h3>
            </div>
            
            <div class="message-info">
                <div class="message-label">Sujet :</div>
                <div>{{ $message->subject ?? 'Pas de sujet' }}</div>
            </div>
            <div class="message-info">
                <div class="message-label">Date :</div>
                <div>{{ $message->created_at->format('d/m/Y à H:i:s') }}</div>
            </div>
            <div class="message-info">
                <div class="message-label">Type :</div>
                <div>{{ $message->type ?? 'Message général' }}</div>
            </div>
            <div class="message-info">
                <div class="message-label">Statut :</div>
                <div>{{ $message->read_at ? 'Lu le ' . $message->read_at->format('d/m/Y à H:i') : 'Non lu' }}</div>
            </div>

            @if($message->content)
                <div class="message-content">
                    <strong>Contenu :</strong><br>
                    {{ $message->content }}
                </div>
            @endif

            <div class="message-meta">
                @if($message->archived_at)
                    Archivé le {{ $message->archived_at->format('d/m/Y à H:i:s') }}
                @endif
            </div>
        </div>
    @empty
        <p>Aucun message trouvé.</p>
    @endforelse

    <div class="footer" style="margin-top: 40px; text-align: center; font-size: 10px; color: #666;">
        <p>Document généré automatiquement par FarmShop</p>
        <p>Conformément au Règlement Général sur la Protection des Données (RGPD)</p>
    </div>
</body>
</html>
