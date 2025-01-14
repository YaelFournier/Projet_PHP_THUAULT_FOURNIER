<?php
try {
    // Chemin relatif vers la base de données
    $databasePath = realpath(__DIR__ . '/database/BD.sqlite');
    
    // Vérification que le fichier existe
    if (!$databasePath) {
        throw new Exception("Le fichier de base de données n'existe pas au chemin spécifié.");
    }
    
    // Connexion à SQLite
    $pdo = new PDO('sqlite:' . $databasePath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion réussie à la base de données SQLite !<br>";

    // Test : Vérifier la présence de tables dans la base
    $query = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $query->fetchAll(PDO::FETCH_ASSOC);

    if (empty($tables)) {
        echo "Aucune table trouvée dans la base de données.";
    } else {
        echo "Tables trouvées dans la base de données :<br>";
        foreach ($tables as $table) {
            echo "- " . htmlspecialchars($table['name']) . "<br>";
        }
    }
} catch (PDOException $e) {
    // Gestion des erreurs liées à PDO
    exit("Erreur de connexion ou d'exécution : " . $e->getMessage());
} catch (Exception $e) {
    // Gestion des erreurs générales
    exit("Erreur : " . $e->getMessage());
}
?>
