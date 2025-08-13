<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSSLSecurity extends Command
{
    protected $signature = 'app:test-ssl-security';
    protected $description = 'Tester la sécurité SSL et HTTPS';

    public function handle()
    {
        $this->info('=== TEST SÉCURITÉ SSL/HTTPS ===');

        $domain = 'farmshop-production.up.railway.app';
        $httpsUrl = "https://{$domain}";
        $httpUrl = "http://{$domain}";

        // 1. Test de disponibilité HTTPS
        $this->info('1. Test de disponibilité HTTPS...');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $httpsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $sslError = curl_error($ch);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 400) {
            $this->info("✅ HTTPS disponible (Code: {$httpCode})");
        } else {
            $this->error("❌ HTTPS non disponible (Code: {$httpCode})");
            if ($sslError) {
                $this->error("Erreur SSL: {$sslError}");
            }
        }

        // 2. Test des headers de sécurité
        $this->info('2. Test des headers de sécurité...');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $httpsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $headers = curl_exec($ch);
        curl_close($ch);

        $securityHeaders = [
            'Strict-Transport-Security' => 'HSTS',
            'X-Frame-Options' => 'Protection Clickjacking',
            'X-Content-Type-Options' => 'Protection MIME',
            'X-XSS-Protection' => 'Protection XSS',
            'Referrer-Policy' => 'Politique de référent'
        ];

        foreach ($securityHeaders as $header => $description) {
            if (stripos($headers, $header) !== false) {
                $this->info("✅ {$description} ({$header}) présent");
            } else {
                $this->warn("⚠️  {$description} ({$header}) manquant");
            }
        }

        // 3. Test du certificat SSL
        $this->info('3. Informations sur le certificat SSL...');
        $context = stream_context_create([
            "ssl" => [
                "capture_peer_cert" => true,
                "verify_peer" => true,
                "verify_peer_name" => true,
            ],
        ]);

        $client = @stream_socket_client(
            "ssl://{$domain}:443",
            $errno,
            $errstr,
            30,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if ($client) {
            $cert = stream_context_get_params($client);
            if (isset($cert['options']['ssl']['peer_certificate'])) {
                $certInfo = openssl_x509_parse($cert['options']['ssl']['peer_certificate']);
                $this->info("✅ Certificat SSL valide");
                $this->info("Émetteur: " . $certInfo['issuer']['CN'] ?? 'N/A');
                $this->info("Sujet: " . $certInfo['subject']['CN'] ?? 'N/A');
                $this->info("Valide jusqu'au: " . date('Y-m-d H:i:s', $certInfo['validTo_time_t']));
            }
            fclose($client);
        } else {
            $this->error("❌ Impossible de vérifier le certificat SSL");
            $this->error("Erreur: {$errstr}");
        }

        // 4. Test de redirection HTTP vers HTTPS
        $this->info('4. Test de redirection HTTP -> HTTPS...');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $httpUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $redirectUrl = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
        curl_close($ch);

        if ($httpCode >= 301 && $httpCode <= 308 && str_starts_with($redirectUrl, 'https://')) {
            $this->info("✅ Redirection HTTP -> HTTPS active (Code: {$httpCode})");
            $this->info("Redirection vers: {$redirectUrl}");
        } else {
            $this->warn("⚠️  Redirection HTTP -> HTTPS non configurée");
        }

        // 5. Recommandations
        $this->info('5. Score de sécurité et recommandations...');
        $score = 0;
        $recommendations = [];

        if ($httpCode >= 200 && $httpCode < 400) $score += 25;
        if (stripos($headers, 'Strict-Transport-Security') !== false) $score += 20;
        if (stripos($headers, 'X-Frame-Options') !== false) $score += 15;
        if (stripos($headers, 'X-Content-Type-Options') !== false) $score += 15;
        if (stripos($headers, 'X-XSS-Protection') !== false) $score += 15;
        if ($httpCode >= 301 && $httpCode <= 308) $score += 10;

        $this->info("📊 Score de sécurité: {$score}/100");

        if ($score >= 80) {
            $this->info("🎉 Excellente sécurité SSL!");
        } elseif ($score >= 60) {
            $this->info("✅ Bonne sécurité SSL");
        } else {
            $this->warn("⚠️  Sécurité SSL à améliorer");
        }

        $this->info('=== FIN TEST SÉCURITÉ ===');
    }
}
