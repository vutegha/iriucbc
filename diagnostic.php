<!DOCTYPE html>
<html>
<head>
    <title>Diagnostic Actualités</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: red; }
        .success { color: green; }
        .warning { color: orange; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Diagnostic des Actualités</h1>
    
    <?php
    $host = 'localhost';
    $dbname = 'iriadmin';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo '<h2 class="success">✅ Connexion à la base de données réussie</h2>';
        
        // Vérifier les colonnes
        $stmt = $pdo->query("SHOW COLUMNS FROM actualites");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $hasSlug = false;
        foreach ($columns as $column) {
            if ($column['Field'] === 'slug') {
                $hasSlug = true;
                break;
            }
        }
        
        if ($hasSlug) {
            echo '<h3 class="success">✅ Colonne "slug" trouvée</h3>';
        } else {
            echo '<h3 class="error">❌ Colonne "slug" manquante</h3>';
        }
        
        // Compter les actualités
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM actualites");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "<p><strong>Nombre total d'actualités:</strong> $count</p>";
        
        if ($count > 0) {
            // Afficher les actualités
            $stmt = $pdo->query("SELECT id, titre, slug, created_at FROM actualites ORDER BY id DESC LIMIT 10");
            $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<h3>Dernières actualités:</h3>';
            echo '<table>';
            echo '<tr><th>ID</th><th>Titre</th><th>Slug</th><th>Date</th><th>Test URL</th></tr>';
            
            foreach ($actualites as $actu) {
                $slug = $actu['slug'] ?? '';
                $slugStatus = empty($slug) ? '<span class="error">PAS DE SLUG</span>' : '<span class="success">' . htmlspecialchars($slug) . '</span>';
                
                echo '<tr>';
                echo '<td>' . $actu['id'] . '</td>';
                echo '<td>' . htmlspecialchars(substr($actu['titre'], 0, 30)) . '...</td>';
                echo '<td>' . $slugStatus . '</td>';
                echo '<td>' . $actu['created_at'] . '</td>';
                
                if (!empty($slug)) {
                    $testUrl = "http://127.0.0.1:8000/admin/actualite/" . urlencode($slug) . "/show";
                    echo '<td><a href="' . $testUrl . '" target="_blank">Tester</a></td>';
                } else {
                    echo '<td><span class="error">Impossible</span></td>';
                }
                echo '</tr>';
            }
            echo '</table>';
            
            // Compter les slugs manquants
            $stmt = $pdo->query("SELECT COUNT(*) as null_slugs FROM actualites WHERE slug IS NULL OR slug = ''");
            $nullSlugs = $stmt->fetch(PDO::FETCH_ASSOC)['null_slugs'];
            
            if ($nullSlugs > 0) {
                echo '<h3 class="warning">⚠️ ' . $nullSlugs . ' actualité(s) sans slug</h3>';
                echo '<p>Ces actualités ont besoin d\'un slug pour fonctionner avec la nouvelle route.</p>';
            } else {
                echo '<h3 class="success">✅ Toutes les actualités ont un slug</h3>';
            }
        } else {
            echo '<p class="warning">Aucune actualité trouvée dans la base de données.</p>';
        }
        
    } catch (PDOException $e) {
        echo '<h2 class="error">❌ Erreur de connexion: ' . htmlspecialchars($e->getMessage()) . '</h2>';
    }
    ?>
    
    <h3>Liens utiles:</h3>
    <ul>
        <li><a href="http://127.0.0.1:8000/admin/actualite" target="_blank">Index des actualités</a></li>
        <li><a href="http://127.0.0.1:8000/admin/actualite/create" target="_blank">Créer une nouvelle actualité</a></li>
    </ul>
    
</body>
</html>
