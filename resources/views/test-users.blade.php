<!DOCTYPE html>
<html>
<head>
    <title>Test Users - FarmShop</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .role-user { background-color: #e8f5e8; }
        .role-admin { background-color: #fff2cc; }
        .role-superuser { background-color: #ffe6e6; }
    </style>
</head>
<body>
    <h1>Utilisateurs FarmShop - Test</h1>
    
    <p><strong>Total:</strong> {{ $users->count() }} utilisateurs</p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Username</th>
                <th>Email</th>
                <th>Rôles</th>
                <th>Newsletter</th>
                <th>Créé le</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="role-{{ $user->getPrimaryRole() }}">
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @foreach($user->roles as $role)
                        <span style="background: #007cba; color: white; padding: 2px 6px; border-radius: 3px; font-size: 12px;">
                            {{ $role->name }}
                        </span>
                    @endforeach
                </td>
                <td>
                    @if($user->is_newsletter_subscribed)
                        <span style="color: green;">✓ Abonné</span>
                    @else
                        <span style="color: red;">✗ Non abonné</span>
                    @endif
                </td>
                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if($user->deleted_at)
                        <span style="color: red;">Supprimé</span>
                    @else
                        <span style="color: green;">Actif</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        <h3>Connexions de test :</h3>
        <ul>
            <li><strong>Super Admin:</strong> superadmin@farmshop.com / password123</li>
            <li><strong>Admin:</strong> admin@farmshop.com / password123</li>
            <li><strong>User:</strong> user@farmshop.com / password123</li>
        </ul>
    </div>

    <div style="margin-top: 20px;">
        <a href="/dashboard" style="background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Aller au Dashboard
        </a>
        <a href="/login" style="background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-left: 10px;">
            Se connecter
        </a>
    </div>
</body>
</html>
