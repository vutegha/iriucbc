# Guide de Migration Sécurisée - Projet IRIUCBC

## Principe général
Pour toutes les modifications de structure de base de données, nous suivons cette approche :
1. **JAMAIS** de suppression directe de colonnes ou tables
2. **TOUJOURS** créer les nouvelles structures d'abord
3. **MIGRER** les données existantes
4. **VÉRIFIER** que tout fonctionne
5. **OPTIONNELLEMENT** supprimer les anciennes structures (commenté par défaut)

## Étapes pour chaque migration

### 1. Sauvegarde obligatoire
```bash
# Avant TOUTE migration
mysqldump -u [utilisateur] -p [base_de_donnees] > backup_$(date +%Y%m%d_%H%M%S).sql
```

### 2. Structure des migrations
Chaque migration suit ce pattern :
```php
public function up(): void
{
    // 1. Créer nouvelles structures
    Schema::create('nouvelle_table', function (Blueprint $table) {
        // définition
    });

    // 2. Migrer les données existantes
    $this->migrateExistingData();

    // 3. OPTIONNEL : Supprimer anciennes structures (COMMENTÉ par défaut)
    /*
    Schema::table('ancienne_table', function (Blueprint $table) {
        $table->dropColumn('ancienne_colonne');
    });
    */
}

private function migrateExistingData()
{
    // Logique de migration des données
    // Avec gestion d'erreurs et logging
}
```

### 3. Commandes de vérification
Chaque migration importante doit avoir une commande de vérification associée.

## Tables concernées par les migrations

### Publications ✅ (Déjà traité)
- Migration `auteur_id` → relation many-to-many avec table pivot
- Commande : `php artisan publication:migrate-authors`

### Autres tables à traiter :
1. **Actualités** - Relations avec auteurs
2. **Médias** - Relations avec projets
3. **Projets** - Relations avec services
4. **Rapports** - Relations avec auteurs/catégories
5. **Newsletters** - Optimisation structure

## Règles de développement

### ❌ À NE JAMAIS FAIRE
```php
// INTERDIT - Suppression directe
Schema::dropColumn('colonne');
Schema::dropTable('table');
$table->dropForeign(['relation_id']);
```

### ✅ À TOUJOURS FAIRE
```php
// AUTORISÉ - Avec migration des données
/*
// Décommenter seulement après vérification complète
Schema::table('table', function (Blueprint $table) {
    $table->dropColumn('ancienne_colonne');
});
*/
```

## Checklist pour chaque migration

- [ ] Sauvegarde de la base de données
- [ ] Migration des données existantes
- [ ] Script de vérification
- [ ] Tests des fonctionnalités affectées
- [ ] Documentation des changements
- [ ] Suppression commentée des anciennes structures

## Commandes de vérification disponibles

```bash
# Vérifier la migration des publications
php artisan publication:migrate-authors --verify

# À créer pour les autres tables
php artisan actualite:migrate-authors --verify
php artisan media:migrate-relations --verify
php artisan projet:migrate-relations --verify
```
