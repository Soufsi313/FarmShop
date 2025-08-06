<?php

// Script simple pour corriger le statut via PDO (sans Laravel)

try {
    // Configuration de base de données (ajustez selon votre .env)
    $host = 'localhost';
    $dbname = 'farmshop'; // ou le nom de votre base
    $username = 'root';
    $password = ''; // ou votre mot de passe MySQL
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CORRECTION DIRECTE EN BASE DE DONNÉES ===\n\n";
    
    // Vérifier l'état actuel
    $stmt = $pdo->prepare("SELECT * FROM order_locations WHERE order_number = ?");
    $stmt->execute(['LOC-202508034682']);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$order) {
        echo "❌ Commande LOC-202508034682 non trouvée\n";
        exit(1);
    }
    
    echo "✅ Commande trouvée: {$order['order_number']}\n";
    echo "📊 Statut actuel: {$order['status']}\n";
    echo "📅 Date de début: {$order['start_date']}\n";
    echo "📅 Date de fin: {$order['end_date']}\n\n";
    
    $now = new DateTime();
    $startDate = new DateTime($order['start_date']);
    $endDate = new DateTime($order['end_date']);
    
    if ($now >= $startDate && $now < $endDate) {
        echo "🔄 La location devrait être ACTIVE maintenant.\n";
        echo "⚡ Correction du statut en base...\n";
        
        // Mettre à jour directement en base
        $updateStmt = $pdo->prepare("
            UPDATE order_locations 
            SET status = 'active', 
                started_at = ?, 
                updated_at = NOW() 
            WHERE order_number = ?
        ");
        
        $updateStmt->execute([$order['start_date'], 'LOC-202508034682']);
        
        echo "✅ Statut mis à jour vers: active\n";
        echo "📅 Démarrée le: {$order['start_date']}\n";
        echo "⏰ Se terminera le: {$order['end_date']}\n\n";
        
        // Vérification
        $verifyStmt = $pdo->prepare("SELECT status, started_at FROM order_locations WHERE order_number = ?");
        $verifyStmt->execute(['LOC-202508034682']);
        $updated = $verifyStmt->fetch(PDO::FETCH_ASSOC);
        
        echo "=== VÉRIFICATION ===\n";
        echo "✅ Nouveau statut: {$updated['status']}\n";
        echo "✅ Démarrée le: {$updated['started_at']}\n";
        
        echo "\n🎉 CORRECTION RÉUSSIE!\n";
        echo "💡 Votre location est maintenant active.\n";
        echo "📧 Vous devriez recevoir un email de notification bientôt.\n";
        
    } elseif ($now < $startDate) {
        echo "⏳ La location n'a pas encore commencé\n";
    } else {
        echo "🔴 La location devrait être terminée\n";
        echo "🔄 Correction vers 'completed'...\n";
        
        $updateStmt = $pdo->prepare("
            UPDATE order_locations 
            SET status = 'completed', 
                completed_at = ?, 
                updated_at = NOW() 
            WHERE order_number = ?
        ");
        
        $updateStmt->execute([$order['end_date'], 'LOC-202508034682']);
        echo "✅ Statut corrigé vers: completed\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "💡 Vérifiez vos paramètres de base de données dans le script.\n";
}
