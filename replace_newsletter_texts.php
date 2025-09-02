<?php

// Script pour remplacer tous les textes français dans le template newsletters
$templatePath = 'c:\Users\Master\Desktop\FarmShop\resources\views\admin\newsletters\index.blade.php';
$content = file_get_contents($templatePath);

// Remplacements pour les boutons et textes
$replacements = [
    // Bouton Modifier
    'title="Modifier la newsletter"' => 'title="{{ __(\\'newsletters.tooltips.edit_newsletter\\') }}"',
    '>Modifier</a>' => '>{{ __(\\'newsletters.actions.edit\\') }}</a>',
    
    // Bouton Verrouillée
    'title="Newsletter déjà envoyée - modification impossible"' => 'title="{{ __(\\'newsletters.tooltips.locked_sent\\') }}"',
    '>Verrouillée</span>' => '>{{ __(\\'newsletters.actions.locked\\') }}</span>',
    
    // Bouton Dupliquer
    'title="Dupliquer cette newsletter"' => 'title="{{ __(\\'newsletters.tooltips.duplicate_newsletter\\') }}"',
    '>Dupliquer</a>' => '>{{ __(\\'newsletters.actions.duplicate\\') }}</a>',
    
    // Bouton Envoyer
    'title="Envoyer maintenant"' => 'title="{{ __(\\'newsletters.tooltips.send_now\\') }}"',
    '>Envoyer</button>' => '>{{ __(\\'newsletters.actions.send\\') }}</button>',
    'onclick="return confirm(\'Êtes-vous sûr de vouloir envoyer cette newsletter maintenant ?\')"' => 'onclick="return confirm(\'{{ __(\\'newsletters.confirmations.send_now\\') }}\')"',
    
    // Bouton Annuler
    'title="Annuler la programmation"' => 'title="{{ __(\\'newsletters.tooltips.cancel_schedule\\') }}"',
    '>Annuler</button>' => '>{{ __(\\'newsletters.actions.cancel\\') }}</button>',
    'onclick="return confirm(\'Annuler la programmation de cette newsletter ?\')"' => 'onclick="return confirm(\'{{ __(\\'newsletters.confirmations.cancel_schedule\\') }}\')"',
    
    // Bouton Renvoyer
    'title="Renvoyer cette newsletter à tous les abonnés"' => 'title="{{ __(\\'newsletters.tooltips.resend_newsletter\\') }}"',
    '>Renvoyer</button>' => '>{{ __(\\'newsletters.actions.resend\\') }}</button>',
    'onclick="return confirm(\'Êtes-vous sûr de vouloir renvoyer cette newsletter à tous les abonnés actuels ?\')"' => 'onclick="return confirm(\'{{ __(\\'newsletters.confirmations.resend\\') }}\')"',
    
    // Bouton Supprimer
    'title="Supprimer définitivement"' => 'title="{{ __(\\'newsletters.tooltips.delete_permanently\\') }}"',
    '>Supprimer</button>' => '>{{ __(\\'newsletters.actions.delete\\') }}</button>',
    'onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer définitivement cette newsletter ? Cette action est irréversible.\')"' => 'onsubmit="return confirm(\'{{ __(\\'newsletters.confirmations.delete\\') }}\')"',
    
    // Messages d\'état vide
    '>Aucune newsletter trouvée</h3>' => '>{{ __(\\'newsletters.empty.title\\') }}</h3>',
    'Aucune newsletter ne correspond à vos critères.' => '{{ __(\\'newsletters.empty.no_results\\') }}',
    'Créez votre première newsletter pour commencer.' => '{{ __(\\'newsletters.empty.no_newsletters\\') }}',
    '>Créer ma première newsletter</a>' => '>{{ __(\\'newsletters.empty.create_first\\') }}</a>',
];

foreach ($replacements as $search => $replace) {
    $content = str_replace($search, $replace, $content);
}

file_put_contents($templatePath, $content);
echo "Remplacements terminés!\n";
