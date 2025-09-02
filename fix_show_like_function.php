<?php

$filePath = 'resources/views/web/products/show.blade.php';
$content = file_get_contents($filePath);

// Le nouveau code JavaScript simple pour toggleLike
$newToggleLike = <<<'JS'
async function toggleLike(productSlug) {
    console.log('🎯 toggleLike appelé pour:', productSlug);
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            throw new Error('CSRF token non trouvé');
        }
        
        const response = await fetch(`/web/likes/products/${productSlug}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const data = await response.json();
        console.log('✅ Réponse reçue:', data);
        
        if (data.success) {
            const btn = document.getElementById(`like_btn_${productSlug}`);
            const icon = btn.querySelector('svg');
            const text = btn.querySelector('span');
            const likesCountElement = document.getElementById(`likes_count_${productSlug}`);
            const likesTextElement = document.getElementById(`likes_text_${productSlug}`);
            
            const isLiked = data.data.is_liked || data.data.liked;
            const likesCount = data.data.likes_count || 0;
            
            console.log('🔄 Mise à jour UI - isLiked:', isLiked, 'likesCount:', likesCount);
            
            // Mettre à jour le bouton
            if (isLiked) {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-red-500');
                icon.setAttribute('fill', 'currentColor');
                text.textContent = 'J\'aime';
                btn.classList.add('text-red-500', 'border-red-300');
                btn.classList.remove('text-gray-700');
            } else {
                icon.classList.remove('text-red-500');
                icon.classList.add('text-gray-400');
                icon.setAttribute('fill', 'none');
                text.textContent = 'Aimer';
                btn.classList.remove('text-red-500', 'border-red-300');
                btn.classList.add('text-gray-700');
            }
            
            // Mettre à jour le compteur
            if (likesCountElement) {
                likesCountElement.textContent = likesCount;
            }
            
            // Mettre à jour le texte de description
            if (likesTextElement) {
                if (likesCount === 1) {
                    likesTextElement.textContent = '{{ __('app.products.person_likes') }}';
                } else {
                    likesTextElement.textContent = '{{ __('app.products.people_like') }}';
                }
            }
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || "Erreur lors de l'action", 'error');
        }
    } catch (error) {
        console.error('❌ Erreur dans toggleLike:', error);
        showNotification('Erreur lors de l\'action', 'error');
    }
}
JS;

// Chercher et remplacer la fonction toggleLike
$pattern = '/async function toggleLike\(productSlug\) \{.*?\n\}\n\nasync function toggleWishlist/s';
$replacement = $newToggleLike . "\n\nasync function toggleWishlist";

$newContent = preg_replace($pattern, $replacement, $content);

if ($newContent && $newContent !== $content) {
    file_put_contents($filePath, $newContent);
    echo "✅ Fonction toggleLike remplacée avec succès!\n";
} else {
    echo "❌ Erreur: impossible de remplacer la fonction toggleLike\n";
    echo "Pattern trouvé: " . (preg_match($pattern, $content) ? "OUI" : "NON") . "\n";
}
