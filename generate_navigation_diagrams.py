import matplotlib.pyplot as plt
import matplotlib.patches as mpatches
from matplotlib.patches import FancyBboxPatch, Rectangle, Circle, Arrow
import numpy as np

# Configuration générale
plt.style.use('default')
fig, (ax1, ax2) = plt.subplots(1, 2, figsize=(20, 12))
fig.suptitle('Diagrammes de Navigation - FarmShop', fontsize=20, fontweight='bold', y=0.95)

# Couleurs personnalisées
colors = {
    'start': '#4CAF50',      # Vert
    'process': '#2196F3',    # Bleu
    'decision': '#FF9800',   # Orange
    'endpoint': '#9C27B0',   # Violet
    'payment': '#F44336',    # Rouge
    'success': '#8BC34A',    # Vert clair
    'arrow': '#666666'       # Gris
}

def add_rounded_box(ax, x, y, width, height, text, color, text_color='white'):
    """Ajouter une boîte arrondie avec du texte"""
    box = FancyBboxPatch((x-width/2, y-height/2), width, height,
                        boxstyle="round,pad=0.1", 
                        facecolor=color, 
                        edgecolor='black',
                        linewidth=1.5)
    ax.add_patch(box)
    ax.text(x, y, text, ha='center', va='center', 
           fontsize=9, fontweight='bold', color=text_color,
           wrap=True, bbox=dict(boxstyle="round,pad=0.3", alpha=0))

def add_decision_diamond(ax, x, y, text, color, text_color='white'):
    """Ajouter un losange de décision"""
    diamond_points = np.array([[x, y+0.8], [x+1.2, y], [x, y-0.8], [x-1.2, y]])
    diamond = plt.Polygon(diamond_points, facecolor=color, edgecolor='black', linewidth=1.5)
    ax.add_patch(diamond)
    ax.text(x, y, text, ha='center', va='center', 
           fontsize=8, fontweight='bold', color=text_color)

def add_arrow(ax, start_x, start_y, end_x, end_y, text='', offset=0.3):
    """Ajouter une flèche avec du texte optionnel"""
    ax.annotate('', xy=(end_x, end_y), xytext=(start_x, start_y),
                arrowprops=dict(arrowstyle='->', lw=2, color=colors['arrow']))
    if text:
        mid_x, mid_y = (start_x + end_x) / 2, (start_y + end_y) / 2 + offset
        ax.text(mid_x, mid_y, text, ha='center', va='center', 
               fontsize=7, style='italic', 
               bbox=dict(boxstyle="round,pad=0.2", facecolor='white', alpha=0.8))

# ============= DIAGRAMME DE NAVIGATION - ACHAT =============
ax1.set_title('Navigation - Processus d\'Achat', fontsize=16, fontweight='bold', pad=20)

# Points de navigation pour l'achat
# Page d'accueil / Catalogue
add_rounded_box(ax1, 2, 18, 2.5, 1, 'Page d\'Accueil\n/products', colors['start'])

# Recherche / Filtrage
add_rounded_box(ax1, 2, 16, 2.5, 1, 'Recherche/Filtrage\nProduits', colors['process'])

# Page produit
add_rounded_box(ax1, 2, 14, 2.5, 1, 'Détail Produit\n/products/{slug}', colors['process'])

# Décision: Ajouter au panier
add_decision_diamond(ax1, 2, 12, 'Ajouter au\npanier?', colors['decision'])

# Panier
add_rounded_box(ax1, 6, 14, 2.5, 1, 'Panier\n/cart', colors['process'])

# Connexion/Inscription
add_decision_diamond(ax1, 6, 12, 'Utilisateur\nconnecté?', colors['decision'])

add_rounded_box(ax1, 10, 12, 2.5, 1, 'Connexion/Inscription\n/login /register', colors['process'])

# Checkout
add_rounded_box(ax1, 6, 10, 2.5, 1, 'Checkout\n/checkout', colors['process'])

# Validation commande
add_rounded_box(ax1, 6, 8, 2.5, 1, 'Validation\nCommande', colors['process'])

# Paiement
add_rounded_box(ax1, 6, 6, 2.5, 1, 'Paiement Stripe\n/payment/{order}', colors['payment'])

# Décision paiement
add_decision_diamond(ax1, 6, 4, 'Paiement\nréussi?', colors['decision'])

# Succès
add_rounded_box(ax1, 10, 4, 2.5, 1, 'Confirmation\n/orders/{order}', colors['success'])

# Échec
add_rounded_box(ax1, 2, 4, 2.5, 1, 'Échec Paiement\n/payment-failed', colors['payment'])

# Mes commandes
add_rounded_box(ax1, 10, 2, 2.5, 1, 'Mes Commandes\n/orders', colors['endpoint'])

# Flèches de navigation - Achat
add_arrow(ax1, 2, 17.5, 2, 16.5)  # Accueil -> Recherche
add_arrow(ax1, 2, 15.5, 2, 14.5)  # Recherche -> Produit
add_arrow(ax1, 2, 13.2, 2, 12.8)  # Produit -> Décision panier
add_arrow(ax1, 3.2, 12, 4.8, 13.5, 'Oui')  # Décision -> Panier
add_arrow(ax1, 6, 13.5, 6, 12.8)  # Panier -> Décision connexion
add_arrow(ax1, 7.2, 12, 8.8, 12, 'Non')  # Décision -> Connexion
add_arrow(ax1, 10, 11.5, 6.5, 10.8, 'Après connexion')  # Connexion -> Checkout
add_arrow(ax1, 6, 11.5, 6, 10.5, 'Oui')  # Décision connexion -> Checkout
add_arrow(ax1, 6, 9.5, 6, 8.5)  # Checkout -> Validation
add_arrow(ax1, 6, 7.5, 6, 6.5)  # Validation -> Paiement
add_arrow(ax1, 6, 5.5, 6, 4.8)  # Paiement -> Décision
add_arrow(ax1, 7.2, 4, 8.8, 4, 'Oui')  # Décision -> Succès
add_arrow(ax1, 4.8, 4, 3.2, 4, 'Non')  # Décision -> Échec
add_arrow(ax1, 10, 3.5, 10, 2.5)  # Succès -> Mes commandes
add_arrow(ax1, 0.8, 12, 0.8, 4.5, '')  # Retour depuis décision panier
add_arrow(ax1, 1, 4.5, 2, 4.5, 'Retry')  # Échec -> Retour

# Configuration de l'axe pour l'achat
ax1.set_xlim(-1, 12)
ax1.set_ylim(0, 20)
ax1.set_aspect('equal')
ax1.axis('off')

# ============= DIAGRAMME DE NAVIGATION - LOCATION =============
ax2.set_title('Navigation - Processus de Location', fontsize=16, fontweight='bold', pad=20)

# Points de navigation pour la location
# Page d'accueil / Catalogue location
add_rounded_box(ax2, 2, 18, 2.5, 1, 'Catalogue Location\n/rentals', colors['start'])

# Filtrage par catégorie
add_rounded_box(ax2, 2, 16, 2.5, 1, 'Filtrage Équipements\nDisponibles', colors['process'])

# Page produit location
add_rounded_box(ax2, 2, 14, 2.5, 1, 'Détail Équipement\n/rentals/{slug}', colors['process'])

# Vérification disponibilité
add_decision_diamond(ax2, 2, 12, 'Équipement\ndisponible?', colors['decision'])

# Panier location
add_rounded_box(ax2, 6, 14, 2.5, 1, 'Panier Location\n/cart-location', colors['process'])

# Sélection dates
add_rounded_box(ax2, 6, 12, 2.5, 1, 'Sélection Dates\nLocation', colors['process'])

# Connexion pour location
add_decision_diamond(ax2, 6, 10, 'Utilisateur\nconnecté?', colors['decision'])

add_rounded_box(ax2, 10, 10, 2.5, 1, 'Connexion Requise\n/login', colors['process'])

# Checkout location
add_rounded_box(ax2, 6, 8, 2.5, 1, 'Checkout Location\n/checkout-rental', colors['process'])

# Validation location
add_rounded_box(ax2, 6, 6.5, 2.5, 1, 'Validation\n+ Caution', colors['process'])

# Paiement location
add_rounded_box(ax2, 6, 5, 2.5, 1, 'Paiement + Caution\n/payment-rental/{order}', colors['payment'])

# Décision paiement location
add_decision_diamond(ax2, 6, 3.5, 'Paiement\nréussi?', colors['decision'])

# Succès location
add_rounded_box(ax2, 10, 3.5, 2.5, 1, 'Location Confirmée\n/rental-orders/{order}', colors['success'])

# Suivi location
add_rounded_box(ax2, 10, 2, 2.5, 1, 'Mes Locations\n/rental-orders', colors['endpoint'])

# Gestion retour
add_rounded_box(ax2, 10, 0.5, 2.5, 1, 'Retour Équipement\n+ Inspection', colors['endpoint'])

# Échec location
add_rounded_box(ax2, 2, 3.5, 2.5, 1, 'Échec Paiement\n/payment-failed', colors['payment'])

# Flèches de navigation - Location
add_arrow(ax2, 2, 17.5, 2, 16.5)  # Accueil -> Filtrage
add_arrow(ax2, 2, 15.5, 2, 14.5)  # Filtrage -> Produit
add_arrow(ax2, 2, 13.2, 2, 12.8)  # Produit -> Décision dispo
add_arrow(ax2, 3.2, 12, 4.8, 13.5, 'Oui')  # Décision -> Panier location
add_arrow(ax2, 6, 13.5, 6, 12.5)  # Panier -> Dates
add_arrow(ax2, 6, 11.5, 6, 10.8)  # Dates -> Décision connexion
add_arrow(ax2, 7.2, 10, 8.8, 10, 'Non')  # Décision -> Connexion
add_arrow(ax2, 10, 9.5, 6.5, 8.8, 'Après connexion')  # Connexion -> Checkout
add_arrow(ax2, 6, 9.2, 6, 8.5, 'Oui')  # Décision connexion -> Checkout
add_arrow(ax2, 6, 7.5, 6, 7)  # Checkout -> Validation
add_arrow(ax2, 6, 6, 6, 5.5)  # Validation -> Paiement
add_arrow(ax2, 6, 4.5, 6, 4.3)  # Paiement -> Décision
add_arrow(ax2, 7.2, 3.5, 8.8, 3.5, 'Oui')  # Décision -> Succès
add_arrow(ax2, 4.8, 3.5, 3.2, 3.5, 'Non')  # Décision -> Échec
add_arrow(ax2, 10, 3, 10, 2.5)  # Succès -> Mes locations
add_arrow(ax2, 10, 1.5, 10, 1)  # Mes locations -> Retour
add_arrow(ax2, 0.8, 12, 0.8, 4, '')  # Retour depuis décision dispo
add_arrow(ax2, 1, 4, 2, 4, 'Non disponible')  # Indisponible -> Échec

# Configuration de l'axe pour la location
ax2.set_xlim(-1, 12)
ax2.set_ylim(-1, 20)
ax2.set_aspect('equal')
ax2.axis('off')

# Légende commune
legend_elements = [
    mpatches.Rectangle((0, 0), 1, 1, facecolor=colors['start'], label='Point de départ'),
    mpatches.Rectangle((0, 0), 1, 1, facecolor=colors['process'], label='Processus'),
    mpatches.Rectangle((0, 0), 1, 1, facecolor=colors['decision'], label='Décision'),
    mpatches.Rectangle((0, 0), 1, 1, facecolor=colors['payment'], label='Paiement'),
    mpatches.Rectangle((0, 0), 1, 1, facecolor=colors['success'], label='Succès'),
    mpatches.Rectangle((0, 0), 1, 1, facecolor=colors['endpoint'], label='Point final')
]

fig.legend(handles=legend_elements, loc='lower center', ncol=6, 
          bbox_to_anchor=(0.5, 0.02), fontsize=12)

plt.tight_layout()
plt.subplots_adjust(bottom=0.1)
plt.savefig('diagrammes_navigation_farmshop.png', dpi=300, bbox_inches='tight', 
           facecolor='white', edgecolor='none')
plt.show()

print("Diagrammes de navigation générés avec succès : diagrammes_navigation_farmshop.png")
