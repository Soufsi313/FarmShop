<?php

// Script de surveillance léger pour les transitions de location
// À exécuter périodiquement via une tâche programmée Windows

try {
    // Configuration DB simple
    $host = 'localhost';
    $dbname = 'farmshop';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $now = date('Y-m-d H:i:s');
    $today = date('Y-m-d');
    
    echo "[" . date('Y-m-d H:i:s') . "] === SURVEILLANCE AUTOMATIQUE DES LOCATIONS ===\n";
    
    // 1. Démarrer les locations confirmées dont la date est arrivée
    $stmt = $pdo->prepare("
        SELECT order_number, start_date, end_date 
        FROM order_locations 
        WHERE status = 'confirmed' 
        AND DATE(start_date) <= ? 
        LIMIT 10
    ");
    
    $stmt->execute([$today]);
    $toStart = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($toStart) > 0) {
        echo "🟢 Locations à démarrer: " . count($toStart) . "\n";
        
        foreach ($toStart as $order) {
            $updateStmt = $pdo->prepare("
                UPDATE order_locations 
                SET status = 'active', 
                    started_at = start_date, 
                    updated_at = NOW() 
                WHERE order_number = ?
            ");
            
            $updateStmt->execute([$order['order_number']]);
            echo "   ✅ {$order['order_number']} → active\n";
        }
    }
    
    // 2. Terminer les locations actives dont la date est passée
    $stmt = $pdo->prepare("
        SELECT order_number, start_date, end_date 
        FROM order_locations 
        WHERE status = 'active' 
        AND DATE(end_date) <= ? 
        LIMIT 10
    ");
    
    $stmt->execute([$today]);
    $toComplete = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($toComplete) > 0) {
        echo "🔴 Locations à terminer: " . count($toComplete) . "\n";
        
        foreach ($toComplete as $order) {
            $updateStmt = $pdo->prepare("
                UPDATE order_locations 
                SET status = 'completed', 
                    completed_at = end_date, 
                    updated_at = NOW() 
                WHERE order_number = ?
            ");
            
            $updateStmt->execute([$order['order_number']]);
            echo "   ✅ {$order['order_number']} → completed\n";
        }
    }
    
    if (count($toStart) == 0 && count($toComplete) == 0) {
        echo "✅ Aucune location à mettre à jour\n";
    }
    
    echo "=== FIN DE SURVEILLANCE ===\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
