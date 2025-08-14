<?php
/**
 * Script pour générer un PDF de la documentation API
 * Usage: php generate_api_pdf.php
 */

require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Configuration
$jsonFile = 'storage/api-docs/api-docs.json';
$outputFile = 'api-documentation.pdf';

if (!file_exists($jsonFile)) {
    die("Erreur: Fichier JSON non trouvé. Exécutez d'abord 'php artisan l5-swagger:generate'\n");
}

// Charger les données JSON
$jsonData = json_decode(file_get_contents($jsonFile), true);

if (!$jsonData) {
    die("Erreur: Impossible de lire le fichier JSON\n");
}

// Générer le HTML
$html = generateHtmlFromSwagger($jsonData);

// Configurer DOMPDF
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Sauvegarder le PDF
file_put_contents($outputFile, $dompdf->output());

echo "✅ Documentation PDF générée: $outputFile\n";
echo "📄 Taille: " . formatBytes(filesize($outputFile)) . "\n";
echo "🔗 Endpoints documentés: " . countEndpoints($jsonData) . "\n";

function generateHtmlFromSwagger($data) {
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>' . ($data['info']['title'] ?? 'API Documentation') . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        h2 { color: #34495e; margin-top: 30px; }
        h3 { color: #7f8c8d; }
        .endpoint { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .method { display: inline-block; padding: 4px 8px; border-radius: 3px; font-weight: bold; }
        .get { background: #5cb85c; color: white; }
        .post { background: #5bc0de; color: white; }
        .put { background: #f0ad4e; color: white; }
        .delete { background: #d9534f; color: white; }
        .path { font-family: monospace; background: #f8f9fa; padding: 2px 5px; }
        .description { margin: 10px 0; color: #666; }
        .parameters { margin-top: 10px; }
        .parameter { margin: 5px 0; padding: 5px; background: #f8f9fa; }
        .response { margin: 5px 0; padding: 5px; background: #e8f5e8; }
        .tag-section { page-break-before: auto; margin-top: 40px; }
        .toc { margin: 20px 0; }
        .toc ul { list-style: none; padding-left: 20px; }
        .toc a { text-decoration: none; color: #3498db; }
    </style>
</head>
<body>';

    // En-tête
    $html .= '<h1>' . ($data['info']['title'] ?? 'API Documentation') . '</h1>';
    $html .= '<p><strong>Version:</strong> ' . ($data['info']['version'] ?? '1.0.0') . '</p>';
    $html .= '<p><strong>Description:</strong> ' . ($data['info']['description'] ?? 'Documentation API générée automatiquement') . '</p>';
    
    // Base URL corrigée pour pointer vers l'API
    $baseUrl = 'http://127.0.0.1:8000/api';
    if (isset($data['servers'][0]['url'])) {
        $baseUrl = $data['servers'][0]['url'];
        // Si l'URL ne finit pas par /api, l'ajouter
        if (!str_ends_with($baseUrl, '/api')) {
            $baseUrl = rtrim($baseUrl, '/') . '/api';
        }
    }
    
    $html .= '<p><strong>Base URL API:</strong> ' . $baseUrl . '</p>';
    $html .= '<p><strong>Documentation URL:</strong> http://127.0.0.1:8000/api/documentation</p>';
    $html .= '<p><strong>Format JSON:</strong> http://127.0.0.1:8000/docs/api-docs.json</p>';
    $html .= '<p><strong>Généré le:</strong> ' . date('d/m/Y H:i:s') . '</p>';
    
    // Note importante
    $html .= '<div style="background: #e8f4f8; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0;">';
    $html .= '<h3 style="margin-top: 0; color: #2980b9;">📌 Comment utiliser cette API</h3>';
    $html .= '<p><strong>Documentation interactive :</strong> <a href="http://127.0.0.1:8000/api/documentation">http://127.0.0.1:8000/api/documentation</a></p>';
    $html .= '<p><strong>Authentication :</strong> Utilisez Laravel Sanctum avec le header <code>Authorization: Bearer {token}</code></p>';
    $html .= '<p><strong>Format :</strong> Toutes les requêtes et réponses sont en JSON</p>';
    $html .= '<p><strong>CORS :</strong> Configuré pour accepter les requêtes cross-origin</p>';
    $html .= '</div>';

    // Table des matières
    $html .= '<div class="toc"><h2>Table des matières</h2><ul>';
    $tags = [];
    foreach ($data['paths'] as $path => $methods) {
        foreach ($methods as $method => $details) {
            if (isset($details['tags'])) {
                foreach ($details['tags'] as $tag) {
                    if (!in_array($tag, $tags)) {
                        $tags[] = $tag;
                    }
                }
            }
        }
    }
    foreach ($tags as $tag) {
        $html .= '<li><a href="#tag-' . $tag . '">' . $tag . '</a></li>';
    }
    $html .= '</ul></div>';

    // Grouper par tags
    $groupedPaths = [];
    foreach ($data['paths'] as $path => $methods) {
        foreach ($methods as $method => $details) {
            $tag = $details['tags'][0] ?? 'General';
            if (!isset($groupedPaths[$tag])) {
                $groupedPaths[$tag] = [];
            }
            $groupedPaths[$tag][] = [
                'path' => $path,
                'method' => $method,
                'details' => $details
            ];
        }
    }

    // Générer les sections
    foreach ($groupedPaths as $tag => $endpoints) {
        $html .= '<div class="tag-section">';
        $html .= '<h2 id="tag-' . $tag . '">' . $tag . '</h2>';
        
        foreach ($endpoints as $endpoint) {
            $html .= '<div class="endpoint">';
            $html .= '<h3><span class="method ' . $endpoint['method'] . '">' . strtoupper($endpoint['method']) . '</span> ';
            $html .= '<span class="path">' . $endpoint['path'] . '</span></h3>';
            
            if (isset($endpoint['details']['summary'])) {
                $html .= '<p><strong>' . $endpoint['details']['summary'] . '</strong></p>';
            }
            
            if (isset($endpoint['details']['description'])) {
                $html .= '<div class="description">' . $endpoint['details']['description'] . '</div>';
            }
            
            // Paramètres
            if (isset($endpoint['details']['parameters'])) {
                $html .= '<div class="parameters"><h4>Paramètres:</h4>';
                foreach ($endpoint['details']['parameters'] as $param) {
                    $html .= '<div class="parameter">';
                    $html .= '<strong>' . $param['name'] . '</strong>';
                    $html .= ' (' . $param['in'] . ')';
                    if (isset($param['required']) && $param['required']) {
                        $html .= ' <em>requis</em>';
                    }
                    if (isset($param['description'])) {
                        $html .= ' - ' . $param['description'];
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
            
            // Réponses
            if (isset($endpoint['details']['responses'])) {
                $html .= '<div class="responses"><h4>Réponses:</h4>';
                foreach ($endpoint['details']['responses'] as $code => $response) {
                    $html .= '<div class="response">';
                    $html .= '<strong>' . $code . '</strong>';
                    if (isset($response['description'])) {
                        $html .= ' - ' . $response['description'];
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }
        $html .= '</div>';
    }

    $html .= '</body></html>';
    return $html;
}

function countEndpoints($data) {
    $count = 0;
    foreach ($data['paths'] as $path => $methods) {
        $count += count($methods);
    }
    return $count;
}

function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
}
