<!DOCTYPE html>
<html>
<head>
    <title>Générateur de Slugs</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #005a87; }
        .result { margin-top: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Générateur de Slugs pour Actualités</h1>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
        $host = 'localhost';
        $dbname = 'iriadmin';
        $username = 'root';
        $password = '';

        function generateSlug($title, $date) {
            // Supprimer les accents et caractères spéciaux
            $title = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
            
            // Convertir en minuscules et remplacer les espaces par des tirets
            $slug = strtolower($title);
            $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
            $slug = preg_replace('/[\s-]+/', '-', $slug);
            $slug = trim($slug, '-');
            
            // Ajouter la date au début (format YYYYMMDD)
            $datePrefix = date('Ymd', strtotime($date));
            
            return $datePrefix . '-' . $slug;
        }

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            echo '<div class="result">';
            echo '<h3>Résultats de la génération</h3>';
            
            // Récupérer les actualités sans slug
            $stmt = $pdo->query("SELECT id, titre, created_at FROM actualites WHERE slug IS NULL OR slug = ''");
            $actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<p><strong>Actualités sans slug trouvées:</strong> " . count($actualites) . "</p>";
            
            if (count($actualites) > 0) {
                $updateStmt = $pdo->prepare("UPDATE actualites SET slug = ? WHERE id = ?");
                
                echo '<ul>';
                foreach ($actualites as $actualite) {
                    $slug = generateSlug($actualite['titre'], $actualite['created_at']);
                    
                    // Vérifier l'unicité du slug
                    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM actualites WHERE slug = ? AND id != ?");
                    $checkStmt->execute([$slug, $actualite['id']]);
                    $count = $checkStmt->fetchColumn();
                    
                    // Si le slug existe déjà, ajouter un suffixe
                    if ($count > 0) {
                        $suffix = 1;
                        $originalSlug = $slug;
                        do {
                            $slug = $originalSlug . '-' . $suffix;
                            $checkStmt->execute([$slug, $actualite['id']]);
                            $count = $checkStmt->fetchColumn();
                            $suffix++;
                        } while ($count > 0);
                    }
                    
                    // Mettre à jour
                    $updateStmt->execute([$slug, $actualite['id']]);
                    
                    echo "<li>ID {$actualite['id']}: " . htmlspecialchars(substr($actualite['titre'], 0, 50)) . "... → <strong>" . htmlspecialchars($slug) . "</strong></li>";
                }
                echo '</ul>';
                
                echo '<p class="success"><strong>✅ Slugs générés avec succès!</strong></p>';
                echo '<p><a href="diagnostic.php">Vérifier les résultats</a></p>';
            } else {
                echo '<p class="success">✅ Toutes les actualités ont déjà un slug.</p>';
            }
            echo '</div>';
            
        } catch (PDOException $e) {
            echo '<div class="result error"><h3>❌ Erreur</h3><p>' . htmlspecialchars($e->getMessage()) . '</p></div>';
        }
    } else {
    ?>
        <p>Ce script va générer des slugs pour toutes les actualités qui n'en ont pas.</p>
        <p><strong>Format du slug:</strong> YYYYMMDD-titre-de-l-actualite</p>
        
        <form method="POST">
            <button type="submit" name="generate">Générer les slugs manquants</button>
        </form>
        
        <p><a href="diagnostic.php">Voir l'état actuel des actualités</a></p>
    <?php } ?>
    
</body>
</html>
