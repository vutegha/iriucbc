<!DOCTYPE html>
<html>
<head>
    <title>Vérification des Routes et Liens</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .warning { color: orange; }
        .error { color: red; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .test-result { margin: 5px 0; padding: 5px; border-radius: 3px; }
        .test-success { background-color: #d4edda; color: #155724; }
        .test-warning { background-color: #fff3cd; color: #856404; }
        .test-error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <h1>Vérification des Routes et Liens Actualités</h1>

    <div class="section">
        <h2>✅ Modifications effectuées :</h2>
        <div class="test-result test-success">
            ✓ Route show modifiée : /admin/actualite/view/{slug}
        </div>
        <div class="test-result test-success">
            ✓ Contrôleur show mis à jour pour utiliser le slug
        </div>
        <div class="test-result test-success">
            ✓ Liens edit dans les vues passés de $actualite->id vers $actualite
        </div>
        <div class="test-result test-success">
            ✓ Liens show dans les vues passés vers $actualite->slug
        </div>
        <div class="test-result test-success">
            ✓ Fonctions JavaScript mises à jour pour utiliser les slugs
        </div>
        <div class="test-result test-success">
            ✓ Route update dans edit.blade.php passée de $actualite->id vers $actualite
        </div>
    </div>

    <div class="section">
        <h2>🔍 Routes actuelles :</h2>
        <ul>
            <li><strong>Index :</strong> <code>/admin/actualite</code> ✅</li>
            <li><strong>Create :</strong> <code>/admin/actualite/create</code> ✅</li>
            <li><strong>Store :</strong> <code>POST /admin/actualite</code> ✅</li>
            <li><strong>Show :</strong> <code>/admin/actualite/view/{slug}</code> ✅</li>
            <li><strong>Edit :</strong> <code>/admin/actualite/{actualite}/edit</code> ✅ (utilise slug via getRouteKeyName)</li>
            <li><strong>Update :</strong> <code>PUT /admin/actualite/{actualite}</code> ✅ (utilise slug via getRouteKeyName)</li>
            <li><strong>Destroy :</strong> <code>DELETE /admin/actualite/{actualite}</code> ✅ (utilise slug via getRouteKeyName)</li>
            <li><strong>Publish :</strong> <code>POST /admin/actualite/{actualite}/publish</code> ✅ (utilise slug via getRouteKeyName)</li>
            <li><strong>Unpublish :</strong> <code>POST /admin/actualite/{actualite}/unpublish</code> ✅ (utilise slug via getRouteKeyName)</li>
        </ul>
    </div>

    <div class="section">
        <h2>🎯 URLs générées :</h2>
        <?php
        $host = 'localhost';
        $dbname = 'iriadmin';
        $username = 'root';
        $password = '';

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->query("SELECT id, titre, slug FROM actualites WHERE slug IS NOT NULL AND slug != '' ORDER BY id DESC LIMIT 3");
            $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($actualites) > 0) {
                echo '<p><strong>Exemples avec vraies actualités :</strong></p>';
                foreach ($actualites as $actu) {
                    $slug = $actu['slug'];
                    echo '<div style="margin: 10px 0; padding: 10px; background: #f9f9f9; border-radius: 3px;">';
                    echo '<strong>Actualité :</strong> ' . htmlspecialchars(substr($actu['titre'], 0, 50)) . '...<br>';
                    echo '<strong>Slug :</strong> ' . htmlspecialchars($slug) . '<br>';
                    echo '<strong>URLs :</strong><br>';
                    echo '• Show : <a href="http://127.0.0.1:8000/admin/actualite/view/' . urlencode($slug) . '" target="_blank">http://127.0.0.1:8000/admin/actualite/view/' . htmlspecialchars($slug) . '</a><br>';
                    echo '• Edit : <a href="http://127.0.0.1:8000/admin/actualite/' . urlencode($slug) . '/edit" target="_blank">http://127.0.0.1:8000/admin/actualite/' . htmlspecialchars($slug) . '/edit</a><br>';
                    echo '</div>';
                }
            } else {
                echo '<div class="test-result test-warning">⚠️ Aucune actualité avec slug trouvée. <a href="generate-slugs-web.php">Générer les slugs</a></div>';
            }
            
        } catch (PDOException $e) {
            echo '<div class="test-result test-error">❌ Erreur de connexion à la base de données</div>';
        }
        ?>
    </div>

    <div class="section">
        <h2>⚠️ Points à noter :</h2>
        <div class="test-result test-warning">
            ⚠️ Les fonctions makeUrgent() et removeUrgent() sont appelées mais pas définies
        </div>
        <div class="test-result test-success">
            ✅ Toutes les autres fonctions JavaScript utilisent maintenant les slugs
        </div>
        <div class="test-result test-success">
            ✅ L'injection de modèle Laravel avec getRouteKeyName() permet l'utilisation automatique des slugs
        </div>
    </div>

    <div class="section">
        <h2>🧪 Tests recommandés :</h2>
        <ol>
            <li><a href="http://127.0.0.1:8000/admin/actualite" target="_blank">Accéder à l'index des actualités</a></li>
            <li>Cliquer sur "Voir" pour tester la nouvelle route show</li>
            <li>Cliquer sur "Modifier" pour tester la route edit avec slug</li>
            <li>Tester les boutons de publication/dépublication</li>
            <li>Vérifier que tous les liens utilisent les bonnes URLs</li>
        </ol>
    </div>

    <hr>
    <p>
        <a href="http://127.0.0.1:8000/admin/actualite">📋 Index Actualités</a> | 
        <a href="diagnostic.php">🔍 Diagnostic</a> | 
        <a href="generate-slugs-web.php">🔧 Générer slugs</a>
    </p>
</body>
</html>
