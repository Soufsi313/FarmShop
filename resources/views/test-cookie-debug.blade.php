<!DOCTYPE html>
<html>
<head>
    <title>Test Debug Bandeau Cookies</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Test Debug Bandeau Cookies</h1>
    
    <div>
        <p><strong>URL actuelle:</strong> {{ url()->current() }}</p>
        <p><strong>Session auth changed:</strong> 
            @if(session()->has('auth_status_changed'))
                Oui ({{ session('auth_status_changed') ? 'true' : 'false' }})
            @else
                Non
            @endif
        </p>
        <p><strong>Toutes les variables de session:</strong></p>
        <pre style="background: #f5f5f5; padding: 10px; max-height: 200px; overflow-y: auto;">{{ json_encode(session()->all(), JSON_PRETTY_PRINT) }}</pre>
    </div>
    
    <hr>
    
    <div id="status"></div>
    <br>
    
    <button onclick="testLocalStorage()">1. Tester localStorage</button>
    <button onclick="testAPI()">2. Tester API</button>
    <button onclick="testBanner()">3. Tester Bandeau</button>
    <button onclick="clearAndTest()">4. Clear + Test</button>
    <button onclick="forceShow()">5. Force Affichage</button>
    
    <br><br>
    
    <div id="cookie-banner" class="hidden" style="position: fixed; bottom: 0; left: 0; right: 0; background: #333; color: white; padding: 20px; z-index: 9999;">
        <div style="text-align: center;">
            <p>🍪 TEST BANDEAU COOKIES - Ce bandeau devrait s'afficher</p>
            <button onclick="hideBanner()">Masquer</button>
        </div>
    </div>

    <script>
        const status = document.getElementById('status');
        
        function log(message) {
            console.log(message);
            status.innerHTML += '<br>' + message;
        }
        
        function testLocalStorage() {
            log('=== TEST LOCALSTORAGE ===');
            log('cookie_consent_given: ' + localStorage.getItem('cookie_consent_given'));
            log('cookie_consent_date: ' + localStorage.getItem('cookie_consent_date'));
        }
        
        async function testAPI() {
            log('=== TEST API ===');
            try {
                const response = await fetch('/api/cookies/preferences', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                log('API Response: ' + JSON.stringify(data, null, 2));
                log('consent_required: ' + data.data.consent_required);
            } catch (error) {
                log('API Error: ' + error.message);
            }
        }
        
        function testBanner() {
            log('=== TEST BANDEAU ===');
            const banner = document.getElementById('cookie-banner');
            log('Banner element exists: ' + (banner ? 'YES' : 'NO'));
            if (banner) {
                log('Banner classes: ' + banner.className);
                log('Banner style display: ' + banner.style.display);
                log('Banner hidden: ' + banner.classList.contains('hidden'));
            }
        }
        
        function clearAndTest() {
            log('=== CLEAR + TEST ===');
            localStorage.removeItem('cookie_consent_given');
            localStorage.removeItem('cookie_consent_date');
            log('localStorage cleared');
            testLocalStorage();
        }
        
        function forceShow() {
            log('=== FORCE SHOW ===');
            const banner = document.getElementById('cookie-banner');
            if (banner) {
                banner.classList.remove('hidden');
                banner.style.display = 'block';
                log('Banner forced to show');
            }
        }
        
        function hideBanner() {
            const banner = document.getElementById('cookie-banner');
            if (banner) {
                banner.classList.add('hidden');
                banner.style.display = 'none';
            }
        }
        
        // Test automatique au chargement
        window.onload = function() {
            log('=== PAGE LOADED ===');
            testLocalStorage();
            testAPI();
            testBanner();
        };
    </script>
</body>
</html>
