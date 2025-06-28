<!-- Test Alpine.js simple -->
<div x-data="{ open: false }">
    <!-- Bouton pour ouvrir -->
    <button @click="open = true" class="btn btn-primary position-fixed top-0 start-0 m-3" style="z-index: 1100;">
        🧪 Test Modal
    </button>
    
    <!-- Modal de test -->
    <div x-show="open" 
         class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
         style="background: rgba(0,0,0,0.7); z-index: 1060;">
        
        <div class="bg-white p-4 rounded">
            <h5>Test Modal Alpine.js</h5>
            <p>Ce modal devrait se fermer quand vous cliquez sur les boutons ci-dessous.</p>
            
            <div class="d-flex gap-2">
                <button @click="open = false" class="btn btn-success">
                    ✅ Fermer (méthode 1)
                </button>
                <button @click="open = !open" class="btn btn-warning">
                    🔄 Toggle (méthode 2)
                </button>
                <button onclick="document.querySelector('[x-data]').setAttribute('x-data', '{ open: false }')" class="btn btn-danger">
                    ⚡ Force Close
                </button>
            </div>
            
            <div class="mt-2">
                <small>État: <span x-text="open ? 'OUVERT' : 'FERMÉ'"></span></small>
            </div>
        </div>
    </div>
</div>

<!-- Bannière cookies originale -->
@include('components.cookie-banner-debug')
