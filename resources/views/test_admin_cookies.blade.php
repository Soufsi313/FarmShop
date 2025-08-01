<!DOCTYPE html>
<html>
<head>
    <title>Test Admin Cookies Debug</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body>
    <div x-data="testCookies" x-init="init()">
        <h1>Test Admin Cookies API</h1>
        
        <div>
            <h2>Authentification</h2>
            <p x-text="authStatus"></p>
        </div>
        
        <div>
            <h2>Test Stats</h2>
            <button @click="testStats()">Tester /api/admin/cookies/stats</button>
            <pre x-text="statsResult"></pre>
        </div>
        
        <div>
            <h2>Test List</h2>
            <button @click="testList()">Tester /api/admin/cookies</button>
            <pre x-text="listResult"></pre>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('testCookies', () => ({
                authStatus: 'Vérification...',
                statsResult: '',
                listResult: '',
                
                init() {
                    this.authStatus = 'User: {{ Auth::user() ? Auth::user()->email : "Non connecté" }}';
                },
                
                async testStats() {
                    try {
                        const response = await fetch('/api/admin/cookies/stats', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.statsResult = JSON.stringify(data, null, 2);
                        } else {
                            this.statsResult = `Erreur ${response.status}: ${await response.text()}`;
                        }
                    } catch (error) {
                        this.statsResult = `Exception: ${error.message}`;
                    }
                },
                
                async testList() {
                    try {
                        const response = await fetch('/api/admin/cookies?page=1&per_page=3', {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.listResult = JSON.stringify(data, null, 2);
                        } else {
                            this.listResult = `Erreur ${response.status}: ${await response.text()}`;
                        }
                    } catch (error) {
                        this.listResult = `Exception: ${error.message}`;
                    }
                }
            }))
        });
    </script>
</body>
</html>
