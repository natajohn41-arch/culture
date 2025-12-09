<?php
/**
 * Script pour exporter les données de la base de données locale
 * et créer des seeders pour les importer en production
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "📦 Export des données de la base de données...\n\n";

// Tables à exporter (dans l'ordre de dépendance)
$tables = [
    'roles' => 'RoleSeeder',
    'langues' => 'LangueSeeder',
    'type_medias' => 'TypeMediaSeeder',
    'type_contenus' => 'TypeContenuSeeder',
    'regions' => 'RegionSeeder',
    'utilisateurs' => 'ExportUsersSeeder',
    'contenus' => 'ExportContenusSeeder',
    'media' => 'ExportMediaSeeder',
    'commentaires' => 'ExportCommentairesSeeder',
];

$exportDir = __DIR__ . '/../database/seeders/exports';
if (!is_dir($exportDir)) {
    mkdir($exportDir, 0755, true);
}

foreach ($tables as $table => $seederName) {
    try {
        if (!DB::getSchemaBuilder()->hasTable($table)) {
            echo "⚠️  Table '$table' n'existe pas, ignorée.\n";
            continue;
        }

        $data = DB::table($table)->get()->toArray();
        $count = count($data);

        if ($count === 0) {
            echo "ℹ️  Table '$table' est vide, ignorée.\n";
            continue;
        }

        echo "📋 Export de '$table' ($count enregistrements)...\n";

        // Convertir les objets en tableaux
        $dataArray = array_map(function($item) {
            return (array) $item;
        }, $data);

        // Créer le contenu du seeder
        $seederContent = "<?php\n\n";
        $seederContent .= "namespace Database\\Seeders\\Exports;\n\n";
        $seederContent .= "use Illuminate\\Database\\Seeder;\n";
        $seederContent .= "use Illuminate\\Support\\Facades\\DB;\n\n";
        $seederContent .= "class {$seederName} extends Seeder\n";
        $seederContent .= "{\n";
        $seederContent .= "    public function run(): void\n";
        $seederContent .= "    {\n";
        $seederContent .= "        \$data = " . var_export($dataArray, true) . ";\n\n";
        $seederContent .= "        foreach (\$data as \$row) {\n";
        $seederContent .= "            DB::table('{$table}')->insertOrIgnore(\$row);\n";
        $seederContent .= "        }\n";
        $seederContent .= "    }\n";
        $seederContent .= "}\n";

        // Sauvegarder le fichier
        $filePath = $exportDir . '/' . $seederName . '.php';
        file_put_contents($filePath, $seederContent);
        echo "✅ Exporté vers: $filePath\n";

    } catch (Exception $e) {
        echo "❌ Erreur lors de l'export de '$table': " . $e->getMessage() . "\n";
    }
}

echo "\n✅ Export terminé!\n";
echo "📝 Les seeders sont dans: $exportDir\n";
echo "💡 Pour les utiliser, ajoutez-les dans DatabaseSeeder.php\n";

