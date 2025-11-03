<!DOCTYPE html>
<html>
<head>
    <title>Test Cookie Banner - Version Simplifiée</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body>
    <h1>🍪 Test Cookie Banner - Version Simplifiée</h1>
    
    <div>
        <p><strong>Statut utilisateur:</strong> 
            @auth
                ✅ Connecté (ID: {{ auth()->id() }})
            @else
                ❌ Invité
            @endauth
        </p>
        
        <p><strong>Session auth_status_changed:</strong> 
            @if(session()->has('auth_status_changed'))
                ✅ Oui ({{ session('auth_status_changed') ? 'true' : 'false' }})
            @else
                ❌ Non
            @endif
        </p>
    </div>
    
    <hr>
    
    <div id="status-log" style="background: #f5f5f5; padding: 10px; height: 200px; overflow-y: auto; margin: 10px 0;"></div>
    
    <div>
        <button onclick="testCookieFlow()">🧪 Test Cookie Flow</button>
        <button onclick="testMainSite()">🔧 Test Fonction Principale</button>
        <button onclick="clearData()">🗑️ Clear Data</button>
        <button onclick="simulateLogin()">👤 Simuler Login</button>
    </div>
    
    <!-- Cookie Banner Test -->
    <div id="cookie-banner" class="hidden" style="position: fixed; bottom: 0; left: 0; right: 0; background: #333; color: white; padding: 20px; z-index: 9999; text-align: center;">
        <p>🍪 BANDEAU COOKIES - Ce bandeau devrait s'afficher pour les utilisateurs connectés sans consentement</p>
        <button onclick="acceptCookies()" style="background: green; color: white; padding: 5px 10px; margin: 0 5px;">Accepter</button>
        <button onclick="hideBanner()" style="background: red; color: white; padding: 5px 10px; margin: 0 5px;">Masquer</button>
    </div>
    
    <script>
        const statusLog = document.getElementById('status-log');
        
        function log(message) {
            console.log(message);
            statusLog.innerHTML += new Date().toLocaleTimeString() + ': ' + message + '<br>';
            statusLog.scrollTop = statusLog.scrollHeight;
        }
        
        function showBanner() {
            document.getElementById('cookie-banner').classList.remove('hidden');
            log('✅ Bandeau affiché');
        }
        
        function hideBanner() {
            document.getElementById('cookie-banner').classList.add('hidden');
            log('❌ Bandeau masqué');
        }
        
        function acceptCookies() {
            hideBanner();
            log('✅ Cookies acceptés');
        }
        
        function clearData() {
            localStorage.removeItem('cookie_consent_given');
            localStorage.removeItem('cookie_consent_date');
            sessionStorage.clear();
            hideBanner();
            log('🗑️ Données effacées');
        }
        
        async function testCookieFlow() {
            log('=== 🧪 DÉBUT TEST COOKIE FLOW ===');
            
            // 1. Vérifier l'état utilisateur
            const isAuth = @auth true @else false @endauth;
            log('👤 Utilisateur connecté: ' + isAuth);
            
            // 2. Vérifier localStorage
            const localConsent = localStorage.getItem('cookie_consent_given');
            log('💾 localStorage consent: ' + localConsent);
            
            // 3. Si utilisateur connecté, tester l'API
            if (isAuth) {
                try {
                    log('🌐 Test API /api/cookies/preferences...');
                    const response = await fetch('/api/cookies/preferences', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        log('📊 API Response: ' + JSON.stringify(data, null, 2));
                        
                        if (data.consent_required) {
                            log('⚠️ Consentement requis - affichage du bandeau');
                            showBanner();
                        } else {
                            log('✅ Consentement déjà donné - pas de bandeau');
                        }
                    } else {
                        log('❌ Erreur API: ' + response.status);
                    }
                } catch (error) {
                    log('❌ Erreur: ' + error.message);
                }
            } else {
                // Pour les invités, vérifier localStorage
                if (localConsent !== 'true') {
                    log('⚠️ Invité sans consentement - affichage du bandeau');
                    showBanner();
                } else {
                    log('✅ Invité avec consentement - pas de bandeau');
                }
            }
            
            log('=== ✅ FIN TEST ===');
        }
        
        async function testMainSite() {
            log('=== 🧪 TEST AVEC FONCTION PRINCIPALE ===');
            
            // Charger la fonction show() du site principal depuis app.blade.php
            try {
                const response = await fetch('/api/cookies/preferences', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    log('📊 API Response: ' + JSON.stringify(data, null, 2));
                    
                    // Test de la logique principale  
                    const banner = document.getElementById('cookie-banner');
                    log('🍪 Élément banner trouvé: ' + (banner ? 'OUI' : 'NON'));
                    
                    if (banner && data.consent_required) {
                        log('🍪 ✅ consent_required = TRUE -> AFFICHAGE DU BANDEAU');
                        banner.classList.remove('hidden');
                        log('🍪 🎯 Bandeau affiché avec logique principale!');
                    } else {
                        log('🍪 ❌ consent_required = FALSE ou banner manquant');
                    }
                } else {
                    log('❌ Erreur API: ' + response.status);
                }
            } catch (error) {
                log('❌ Erreur test principal: ' + error.message);
            }
            
            log('=== ✅ FIN TEST PRINCIPAL ===');
        }
        
        function simulateLogin() {
            log('👤 Simulation login...');
            // Marquer que l'utilisateur vient de se connecter
            sessionStorage.setItem('auth_status_changed', 'true');
            log('✅ Indicateur auth_status_changed ajouté');
            // Rediriger pour simuler un login
            window.location.reload();
        }
        
        // Auto-test au chargement si utilisateur connecté
        document.addEventListener('DOMContentLoaded', function() {
            log('🚀 Page chargée');
            
            @auth
                log('👤 Utilisateur connecté détecté - test automatique');
                setTimeout(testCookieFlow, 500);
            @else
                log('👻 Visiteur invité - test manuel disponible');
            @endauth
        });
    </script>
</body>
</html>
