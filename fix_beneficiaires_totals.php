<?php

/**
 * Script de mise à jour des totaux bénéficiaires
 * 
 * Ce script recalcule les totaux bénéficiaires pour tous les projets existants
 * et corrige les incohérences dans la base de données.
 */

echo "=== Script de correction des totaux bénéficiaires ===\n\n";

// Vous pouvez exécuter ce script avec la commande artisan :
// php artisan tinker
// puis copier-coller le code suivant :

echo "Code à exécuter dans Laravel Tinker :\n\n";
echo "use App\\Models\\Projet;\n\n";

echo "// Récupérer tous les projets avec des bénéficiaires\n";
echo "\$projets = Projet::whereNotNull('beneficiaires_hommes')\n";
echo "                ->orWhereNotNull('beneficiaires_femmes')\n";
echo "                ->get();\n\n";

echo "echo \"Nombre de projets à vérifier: \" . \$projets->count() . \"\\n\\n\";\n\n";

echo "// Corriger chaque projet\n";
echo "\$corriges = 0;\n";
echo "foreach (\$projets as \$projet) {\n";
echo "    \$calculManuel = (\$projet->beneficiaires_hommes ?? 0) + (\$projet->beneficiaires_femmes ?? 0);\n";
echo "    \$totalActuel = \$projet->beneficiaires_total ?? 0;\n";
echo "    \n";
echo "    if (\$totalActuel != \$calculManuel) {\n";
echo "        echo \"Correction projet: {\$projet->nom}\\n\";\n";
echo "        echo \"   Ancien total: {\$totalActuel}, Nouveau total: {\$calculManuel}\\n\";\n";
echo "        \n";
echo "        \$projet->beneficiaires_total = \$calculManuel;\n";
echo "        \$projet->save();\n";
echo "        \$corriges++;\n";
echo "    }\n";
echo "}\n\n";

echo "echo \"\\nNombre de projets corrigés: \" . \$corriges . \"\\n\";\n";
echo "echo \"Correction terminée !\\n\";\n\n";

echo "=== Instructions d'utilisation ===\n";
echo "1. Ouvrez un terminal dans le dossier du projet\n";
echo "2. Exécutez: php artisan tinker\n";
echo "3. Copiez-collez le code ci-dessus\n";
echo "4. Pressez Entrée pour exécuter\n\n";

echo "Ou utilisez cette commande unique :\n\n";
echo "php artisan tinker --execute=\"\n";
echo "use App\\Models\\Projet;\n";
echo "\\\$projets = Projet::all();\n";
echo "foreach (\\\$projets as \\\$projet) {\n";
echo "    \\\$total = (\\\$projet->beneficiaires_hommes ?? 0) + (\\\$projet->beneficiaires_femmes ?? 0);\n";
echo "    if (\\\$projet->beneficiaires_total != \\\$total) {\n";
echo "        \\\$projet->update(['beneficiaires_total' => \\\$total]);\n";
echo "        echo 'Corrigé: ' . \\\$projet->nom . ' - Total: ' . \\\$total . PHP_EOL;\n";
echo "    }\n";
echo "}\n";
echo "echo 'Correction terminée !' . PHP_EOL;\n";
echo "\"\n\n";
