<?php
// db.php
$host = 'localhost';
$dbname = 'gestion_employes';
$user = 'root';
$pass = 'ejnaini1'; // Remplacez par votre mot de passe si nécessaire

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Activer le mode d'erreur PDO pour afficher les exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
