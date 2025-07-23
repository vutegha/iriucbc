# Layout IRI - Charte Graphique Institutionnelle

## 🎨 Palette de Couleurs

### Couleurs Principales
- **Vert Principal** : `#1e472f` - Couleur dominante du logo IRI
- **Vert Secondaire** : `#2d5a3f` - Variante plus claire
- **Orange/Rouge Accent** : `#d2691e` - Couleur d'accentuation du logo
- **Vert Clair** : `#f0f9f4` - Arrière-plans subtils
- **Or** : `#b8860b` - Détails et éléments spéciaux

### Couleurs Utilitaires
- **Gris Moderne** : `#64748b` - Textes secondaires
- **Noir Moderne** : `#1a1a1a` - Textes principaux
- **Blanc** : `#ffffff` - Arrière-plans et textes sur foncé

## 🔤 Typographie

### Polices
- **Poppins** - Titres et éléments importantes (Google Fonts)
- **Inter** - Texte courant et interface (Google Fonts)

### Classes Utilitaires
```css
.font-poppins { font-family: 'Poppins', sans-serif; }
.title-iri { font-family: 'Poppins', sans-serif; font-weight: 700; color: var(--iri-primary); }
.subtitle-iri { font-family: 'Poppins', sans-serif; font-weight: 600; color: var(--iri-secondary); }
```

## 🎯 Classes CSS Personnalisées

### Couleurs
```css
/* Textes */
.text-iri-primary { color: #1e472f; }
.text-iri-secondary { color: #2d5a3f; }
.text-iri-accent { color: #d2691e; }
.text-iri-gold { color: #b8860b; }

/* Arrière-plans */
.bg-iri-primary { background-color: #1e472f; }
.bg-iri-secondary { background-color: #2d5a3f; }
.bg-iri-accent { background-color: #d2691e; }
.bg-iri-light { background-color: #f0f9f4; }

/* Gradients */
.gradient-iri { background: linear-gradient(135deg, #1e472f 0%, #2d5a3f 100%); }
.gradient-iri-accent { background: linear-gradient(135deg, #d2691e 0%, #b8860b 100%); }
```

### Boutons
```css
.btn-iri-primary {
    background: #1e472f;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid #1e472f;
}

.btn-iri-accent {
    background: #d2691e;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid #d2691e;
}
```

### Cartes
```css
.card-iri {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
}

.card-iri:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}
```

## 📱 Composants Réutilisables

### Navigation
```html
<!-- Navigation avec classe nav-iri -->
<nav class="nav-iri fixed top-0 w-full z-50 transition-all duration-300">
    <!-- Contenu de la navigation -->
</nav>
```

### Boutons
```html
<!-- Bouton principal -->
<button class="btn-iri-primary hover-lift">
    <i class="fas fa-arrow-right mr-2"></i>
    En savoir plus
</button>

<!-- Bouton accent -->
<button class="btn-iri-accent hover-lift">
    <i class="fas fa-download mr-2"></i>
    Télécharger
</button>
```

### Cartes
```html
<!-- Carte standard -->
<div class="card-iri p-6 animate-on-scroll">
    <div class="w-12 h-12 bg-iri-primary rounded-lg flex items-center justify-center mb-4">
        <i class="fas fa-icon text-white text-xl"></i>
    </div>
    <h3 class="subtitle-iri text-xl mb-3">Titre de la carte</h3>
    <p class="text-iri-gray mb-4">Description du contenu...</p>
    <a href="#" class="link-iri font-semibold">Lire la suite</a>
</div>
```

### Sections
```html
<!-- Section standard -->
<section class="section-iri">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="title-iri text-4xl mb-4">Titre de Section</h2>
            <div class="divider-iri w-20 mx-auto"></div>
            <p class="text-iri-gray text-lg max-w-2xl mx-auto">Description de la section...</p>
        </div>
        <!-- Contenu de la section -->
    </div>
</section>

<!-- Section alternée -->
<section class="section-iri-alt">
    <!-- Contenu sur arrière-plan clair -->
</section>
```

## 🎭 Animations

### Classes d'Animation
```css
.animate-fadeInUp { animation: fadeInUp 0.6s ease forwards; }
.animate-delay-200 { animation-delay: 0.2s; }
.animate-delay-400 { animation-delay: 0.4s; }
.animate-on-scroll { opacity: 0; } /* Sera animé au scroll */
```

### Effets de Hover
```css
.hover-scale:hover { transform: scale(1.05); }
.hover-lift:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
```

## 📋 Formulaires

### Styles de Formulaire
```html
<div class="form-iri">
    <input type="text" placeholder="Nom complet" class="w-full mb-4">
    <input type="email" placeholder="Email" class="w-full mb-4">
    <textarea placeholder="Message" class="w-full mb-4 h-32"></textarea>
    <button type="submit" class="btn-iri-primary w-full">
        <i class="fas fa-paper-plane mr-2"></i>
        Envoyer
    </button>
</div>
```

## 🔔 Alertes et Notifications

### Alertes
```html
<!-- Succès -->
<div class="alert-iri-success mb-4">
    <i class="fas fa-check-circle mr-2"></i>
    Message de succès
</div>

<!-- Erreur -->
<div class="alert-iri-error mb-4">
    <i class="fas fa-exclamation-triangle mr-2"></i>
    Message d'erreur
</div>
```

### Badges
```html
<!-- Badge principal -->
<span class="badge-iri">
    <i class="fas fa-star mr-1"></i>
    Nouveau
</span>

<!-- Badge accent -->
<span class="badge-iri-accent">
    <i class="fas fa-fire mr-1"></i>
    Populaire
</span>
```

## 🎨 Exemples d'Utilisation

### Hero Section
```html
<section class="gradient-iri text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="font-poppins text-5xl font-bold mb-6 animate-fadeInUp">
            Institut de Recherche Intégré
        </h1>
        <p class="text-xl mb-8 animate-fadeInUp animate-delay-200">
            Innovation • Recherche • Développement
        </p>
        <button class="btn-iri-accent text-lg animate-fadeInUp animate-delay-400">
            Découvrir nos projets
        </button>
    </div>
</section>
```

### Section avec Cartes
```html
<section class="section-iri">
    <div class="container mx-auto px-4">
        <h2 class="title-iri text-4xl text-center mb-12">Nos Domaines d'Expertise</h2>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="card-iri p-6 animate-on-scroll">
                <div class="w-16 h-16 bg-iri-primary rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-leaf text-white text-2xl"></i>
                </div>
                <h3 class="subtitle-iri text-xl mb-3">Développement Durable</h3>
                <p class="text-iri-gray">Recherche et innovation pour un avenir durable...</p>
            </div>
            <!-- Autres cartes -->
        </div>
    </div>
</section>
```

## 🎯 Responsivité

Le layout utilise TailwindCSS pour une responsivité complète :
- **Mobile First** : Design optimisé pour mobile
- **Breakpoints** : sm, md, lg, xl, 2xl
- **Composants flexibles** : Grilles et flexbox adaptatives
- **Navigation mobile** : Menu hamburger avec animations

## 🚀 Performance

### Optimisations
- **Google Fonts** : Préchargement avec `preconnect`
- **CSS Critique** : Styles inline pour un rendu rapide
- **Lazy Loading** : Images et animations au scroll
- **Compression** : Assets minifiés en production

### Bonnes Pratiques
- **Accessibilité** : Contraste, focus, ARIA
- **SEO** : Meta tags, Open Graph
- **Progressive Enhancement** : Fonctionne sans JS
- **Performance** : Critical CSS, optimisation des images

Ce layout moderne et professionnel respecte la charte graphique IRI tout en offrant une expérience utilisateur optimale sur tous les appareils ! 🎉
