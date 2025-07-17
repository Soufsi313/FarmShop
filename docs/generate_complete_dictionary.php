<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

// Connexion directe à la base de données
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=FarmShop', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer toutes les tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Exclure les tables système
    $excludeTables = ['failed_jobs', 'jobs', 'job_batches', 'cache', 'cache_locks', 'migrations', 'password_reset_tokens', 'sessions'];
    $filteredTables = array_filter($tables, function($table) use ($excludeTables) {
        return !in_array($table, $excludeTables);
    });
    
    // Analyser chaque table pour récupérer la structure
    $tableStructures = [];
    foreach ($filteredTables as $tableName) {
        $stmt = $pdo->query("DESCRIBE $tableName");
        $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $tableStructures[$tableName] = [
            'description' => "Table $tableName du système FarmShop",
            'fields' => []
        ];
        
        foreach ($fields as $field) {
            $tableStructures[$tableName]['fields'][] = [
                'name' => $field['Field'],
                'type' => $field['Type'],
                'size' => '',
                'null' => $field['Null'] === 'YES' ? 'OUI' : 'NON',
                'key' => $field['Key'] === 'PRI' ? 'PRI' : ($field['Key'] === 'UNI' ? 'UNI' : ($field['Key'] === 'MUL' ? 'FOR' : '')),
                'default' => $field['Default'] ?: '',
                'description' => "Champ {$field['Field']} de la table $tableName"
            ];
        }
    }
    
} catch (Exception $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage() . "\n";
    exit(1);
}

// Créer un nouveau spreadsheet
$spreadsheet = new Spreadsheet();

// Supprimer la feuille par défaut
$spreadsheet->removeSheetByIndex(0);

// Créer la feuille sommaire
$summarySheet = $spreadsheet->createSheet();
$summarySheet->setTitle('Sommaire');
$spreadsheet->setActiveSheetIndex(0);

// En-tête du sommaire
$summarySheet->setCellValue('A1', 'DICTIONNAIRE DE DONNEES - FARMSHOP');
$summarySheet->setCellValue('A2', 'SOMMAIRE DES TABLES');
$summarySheet->setCellValue('A4', 'Table');
$summarySheet->setCellValue('B4', 'Description');
$summarySheet->setCellValue('C4', 'Nombre de champs');
$summarySheet->setCellValue('D4', 'Feuille');

// Style pour l'en-tête
$summarySheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(16);
$summarySheet->getStyle('A2:D2')->getFont()->setBold(true)->setSize(14);
$summarySheet->getStyle('A4:D4')->getFont()->setBold(true);
$summarySheet->getStyle('A4:D4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E3F2FD');

// Remplir le sommaire
$row = 5;
foreach ($tableStructures as $tableName => $tableData) {
    $summarySheet->setCellValue("A{$row}", $tableName);
    $summarySheet->setCellValue("B{$row}", $tableData['description']);
    $summarySheet->setCellValue("C{$row}", count($tableData['fields']));
    $summarySheet->setCellValue("D{$row}", $tableName);
    $row++;
}

// Ajuster les largeurs des colonnes
$summarySheet->getColumnDimension('A')->setWidth(25);
$summarySheet->getColumnDimension('B')->setWidth(50);
$summarySheet->getColumnDimension('C')->setWidth(15);
$summarySheet->getColumnDimension('D')->setWidth(15);

// Créer une feuille pour chaque table
foreach ($tableStructures as $tableName => $tableData) {
    $sheet = $spreadsheet->createSheet();
    $sheet->setTitle($tableName);
    
    // En-tête de la feuille
    $sheet->setCellValue('A1', "TABLE: " . strtoupper($tableName));
    $sheet->setCellValue('A2', $tableData['description']);
    
    // En-têtes des colonnes
    $sheet->setCellValue('A4', 'Champ');
    $sheet->setCellValue('B4', 'Type');
    $sheet->setCellValue('C4', 'Taille');
    $sheet->setCellValue('D4', 'NULL');
    $sheet->setCellValue('E4', 'Cle');
    $sheet->setCellValue('F4', 'Defaut');
    $sheet->setCellValue('G4', 'Description');
    $sheet->setCellValue('H4', 'Regles de validation');
    $sheet->setCellValue('I4', 'Contraintes');
    
    // Style pour l'en-tête
    $sheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A2:I2')->getFont()->setItalic(true)->setSize(12);
    $sheet->getStyle('A4:I4')->getFont()->setBold(true);
    $sheet->getStyle('A4:I4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('E8F5E8');
    
    // Bordures pour l'en-tête
    $sheet->getStyle('A4:I4')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    
    // Remplir les données
    $row = 5;
    foreach ($tableData['fields'] as $field) {
        $sheet->setCellValue("A{$row}", $field['name']);
        $sheet->setCellValue("B{$row}", $field['type']);
        $sheet->setCellValue("C{$row}", $field['size']);
        $sheet->setCellValue("D{$row}", $field['null']);
        $sheet->setCellValue("E{$row}", $field['key']);
        $sheet->setCellValue("F{$row}", $field['default']);
        $sheet->setCellValue("G{$row}", $field['description']);
        
        // Règles de validation spécifiques
        $validation = '';
        $type = strtolower($field['type']);
        
        if (strpos($type, 'varchar') !== false) {
            preg_match('/varchar\((\d+)\)/', $type, $matches);
            $size = isset($matches[1]) ? $matches[1] : 'N/A';
            $validation = "Chaine de caracteres max {$size}";
        } elseif (strpos($type, 'decimal') !== false) {
            $validation = "Nombre decimal";
        } elseif (strpos($type, 'int') !== false || strpos($type, 'bigint') !== false) {
            $validation = "Nombre entier";
        } elseif (strpos($type, 'tinyint(1)') !== false) {
            $validation = "Booleen (0 ou 1)";
        } elseif (strpos($type, 'enum') !== false) {
            $validation = "Valeurs enumerees";
        } elseif (strpos($type, 'date') !== false) {
            $validation = "Format date YYYY-MM-DD";
        } elseif (strpos($type, 'timestamp') !== false) {
            $validation = "Format datetime YYYY-MM-DD HH:MM:SS";
        } elseif (strpos($type, 'text') !== false) {
            $validation = "Texte long";
        } else {
            $validation = "Selon type {$field['type']}";
        }
        
        $sheet->setCellValue("H{$row}", $validation);
        
        // Contraintes
        $constraints = [];
        if ($field['key'] == 'PRI') $constraints[] = 'Cle primaire';
        if ($field['key'] == 'FOR') $constraints[] = 'Cle etrangere';
        if ($field['key'] == 'UNI') $constraints[] = 'Unique';
        if ($field['null'] == 'NON') $constraints[] = 'Obligatoire';
        
        $sheet->setCellValue("I{$row}", implode(', ', $constraints));
        
        // Bordures pour les données
        $sheet->getStyle("A{$row}:I{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        $row++;
    }
    
    // Ajuster les largeurs des colonnes
    $sheet->getColumnDimension('A')->setWidth(25);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(10);
    $sheet->getColumnDimension('D')->setWidth(8);
    $sheet->getColumnDimension('E')->setWidth(8);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(35);
    $sheet->getColumnDimension('H')->setWidth(30);
    $sheet->getColumnDimension('I')->setWidth(25);
}

// Sauvegarder le fichier
$writer = new Xlsx($spreadsheet);
$writer->save(__DIR__ . '/09_Dictionnaire_Donnees_COMPLET.xlsx');

echo "Dictionnaire de donnees Excel cree avec succes !\n";
echo "Emplacement : " . __DIR__ . "/09_Dictionnaire_Donnees_COMPLET.xlsx\n";
echo "Nombre de tables documentees : " . count($tableStructures) . "\n";
echo "Feuilles creees : Sommaire + " . count($tableStructures) . " tables\n";
echo "\nContenu du fichier Excel :\n";
echo "- Feuille 1: Sommaire de toutes les tables\n";
foreach ($tableStructures as $tableName => $tableData) {
    echo "- Feuille {$tableName}: " . count($tableData['fields']) . " champs documentes\n";
}

?>
