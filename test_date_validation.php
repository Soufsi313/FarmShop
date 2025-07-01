<?php

echo "=== Test de la logique des dates de location ===\n\n";

// Date d'aujourd'hui
$today = new DateTime();
echo "Aujourd'hui: " . $today->format('d/m/Y') . "\n";

// Date de demain (minimum autorisée)
$tomorrow = clone $today;
$tomorrow->add(new DateInterval('P1D'));
echo "Demain (minimum autorisé): " . $tomorrow->format('d/m/Y') . "\n\n";

echo "=== Test 1: Calcul des jours de location ===\n";

function calculateRentalDays($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $interval = $start->diff($end);
    return $interval->days + 1; // +1 pour inclure le jour de début
}

$testCases = [
    ['2025-07-02', '2025-07-02', 1], // Même jour = 1 jour
    ['2025-07-02', '2025-07-03', 2], // 2 jours
    ['2025-07-02', '2025-07-06', 5], // 5 jours
    ['2025-07-02', '2025-07-07', 6], // 6 jours
];

foreach ($testCases as [$start, $end, $expected]) {
    $calculated = calculateRentalDays($start, $end);
    $status = $calculated === $expected ? '✅' : '❌';
    echo "Du $start au $end: $calculated jour(s) (attendu: $expected) $status\n";
}

echo "\n=== Test 2: Contraintes de date maximum ===\n";

function calculateMaxEndDate($startDate, $maxRentalDays) {
    $start = new DateTime($startDate);
    $maxEnd = clone $start;
    $maxEnd->add(new DateInterval('P' . ($maxRentalDays - 1) . 'D'));
    return $maxEnd;
}

$startDate = '2025-07-02';
$maxRentalDays = 5;
$maxEndDate = calculateMaxEndDate($startDate, $maxRentalDays);

echo "Date de début: $startDate\n";
echo "Durée maximum: $maxRentalDays jours\n";
echo "Date de fin maximum calculée: " . $maxEndDate->format('d/m/Y') . "\n";
echo "Devrait être 06/07/2025: " . ($maxEndDate->format('d/m/Y') === '06/07/2025' ? '✅' : '❌') . "\n";

echo "\n=== Test 3: Validation de dates ===\n";

function validateRentalDates($startDate, $endDate, $minDays, $maxDays) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $today = new DateTime();
    $today->setTime(0, 0, 0);
    
    $errors = [];
    
    // 1. Date de début doit être après aujourd'hui
    if ($start <= $today) {
        $errors[] = "La location ne peut pas commencer aujourd'hui";
    }
    
    // 2. Date de fin >= date de début
    if ($end < $start) {
        $errors[] = "La date de fin doit être >= date de début";
    }
    
    // 3. Calculer les jours et vérifier contraintes
    $days = calculateRentalDays($startDate, $endDate);
    
    if ($days < $minDays) {
        $errors[] = "Durée minimum: $minDays jour(s), reçu: $days";
    }
    
    if ($days > $maxDays) {
        $errors[] = "Durée maximum: $maxDays jours, reçu: $days";
    }
    
    return [
        'valid' => empty($errors),
        'days' => $days,
        'errors' => $errors
    ];
}

$validationTests = [
    // Test valide
    ['2025-07-02', '2025-07-06', 1, 5, 'Valide'],
    // Test durée trop courte  
    ['2025-07-02', '2025-07-02', 2, 5, 'Durée trop courte'],
    // Test durée trop longue
    ['2025-07-02', '2025-07-08', 1, 5, 'Durée trop longue'],
    // Test date de début aujourd'hui (simulé)
    [$today->format('Y-m-d'), $tomorrow->format('Y-m-d'), 1, 5, 'Date début aujourd\'hui'],
];

foreach ($validationTests as [$start, $end, $min, $max, $description]) {
    $result = validateRentalDates($start, $end, $min, $max);
    $status = $result['valid'] ? '✅ Valide' : '❌ Invalide';
    echo "$description: $status";
    if (!$result['valid']) {
        echo " - " . implode(', ', $result['errors']);
    }
    echo " ({$result['days']} jours)\n";
}

echo "\n=== Test terminé ===\n";
