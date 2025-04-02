<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

// Détermine l'action à réaliser (liste, ajout, modification ou suppression)
$action = $_GET['action'] ?? 'list';

// CSS commun pour un design épuré et professionnel aux tons clairs
$css = <<<CSS
<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');

    * {
        box-sizing: border-box;
    }
    body {
        margin: 0;
        padding: 0;
        font-family: 'Open Sans', sans-serif;
        background-color: #f5f7fa;
        color: #333;
    }
    .container {
        width: 90%;
        max-width: 1200px;
        margin: 40px auto;
        background: #fff;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #2a9df4;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    table, th, td {
        border: 1px solid #e3e3e3;
    }
    th, td {
        padding: 12px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    a {
        color: #2a9df4;
        text-decoration: none;
        padding: 6px 10px;
        border: 1px solid #2a9df4;
        border-radius: 4px;
        transition: background-color 0.3s, color 0.3s;
    }
    a:hover {
        background-color: #2a9df4;
        color: #fff;
    }
    form {
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #e3e3e3;
        border-radius: 6px;
        background-color: #f9f9f9;
    }
    input, button, select, textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 1em;
    }
    button {
        background-color: #2a9df4;
        color: #fff;
        cursor: pointer;
        border: none;
        transition: background-color 0.3s;
    }
    button:hover {
        background-color: #2380c3;
    }
    .error {
        color: #d9534f;
    }
    .search-form {
        text-align: center;
        margin-bottom: 20px;
    }
    .search-form input[type="text"] {
        max-width: 300px;
        display: inline-block;
    }
    .search-form button {
        display: inline-block;
    }
</style>
CSS;

// ACTION : AJOUTER UN EMPLOYÉ
if ($action === 'add') {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom           = trim($_POST['nom'] ?? '');
        $prenom        = trim($_POST['prenom'] ?? '');
        $email         = trim($_POST['email'] ?? '');
        $poste         = trim($_POST['poste'] ?? '');
        $salaire       = trim($_POST['salaire'] ?? '');
        $date_embauche = trim($_POST['date_embauche'] ?? '');

        if ($nom && $prenom && $email && $poste && $salaire && $date_embauche) {
            $stmt = $pdo->prepare("INSERT INTO employees (nom, prenom, email, poste, salaire, date_embauche) VALUES (:nom, :prenom, :email, :poste, :salaire, :date_embauche)");
            if ($stmt->execute([
                'nom'           => $nom,
                'prenom'        => $prenom,
                'email'         => $email,
                'poste'         => $poste,
                'salaire'       => $salaire,
                'date_embauche' => $date_embauche
            ])) {
                header('Location: gestion_employes.php');
                exit;
            } else {
                $error = "Erreur lors de l'ajout de l'employé.";
            }
        } else {
            $error = "Tous les champs sont obligatoires.";
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Ajouter un Employé</title>
        <?php echo $css; ?>
    </head>
    <body>
        <div class="container">
            <h1>Ajouter un Employé</h1>
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="post" action="gestion_employes.php?action=add">
                <label>Nom :</label>
                <input type="text" name="nom" required>
                
                <label>Prénom :</label>
                <input type="text" name="prenom" required>
                
                <label>Email :</label>
                <input type="email" name="email" required>
                
                <label>Poste :</label>
                <input type="text" name="poste" required>
                
                <label>Salaire :</label>
                <input type="number" name="salaire" required step="0.01">
                
                <label>Date d'embauche :</label>
                <input type="date" name="date_embauche" required>
                
                <button type="submit">Ajouter</button>
            </form>
            <p style="text-align: center;"><a href="gestion_employes.php">Retour à la liste</a></p>
        </div>
    </body>
    </html>
    <?php
    exit;
} elseif ($action === 'edit') {
    // ACTION : MODIFIER UN EMPLOYÉ
    if (!isset($_GET['id'])) {
        header('Location: gestion_employes.php');
        exit;
    }
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM employees WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$employee) {
        die("Employé non trouvé.");
    }
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom           = trim($_POST['nom'] ?? '');
        $prenom        = trim($_POST['prenom'] ?? '');
        $email         = trim($_POST['email'] ?? '');
        $poste         = trim($_POST['poste'] ?? '');
        $salaire       = trim($_POST['salaire'] ?? '');
        $date_embauche = trim($_POST['date_embauche'] ?? '');

        if ($nom && $prenom && $email && $poste && $salaire && $date_embauche) {
            $stmt = $pdo->prepare("UPDATE employees SET nom = :nom, prenom = :prenom, email = :email, poste = :poste, salaire = :salaire, date_embauche = :date_embauche WHERE id = :id");
            if ($stmt->execute([
                'nom'           => $nom,
                'prenom'        => $prenom,
                'email'         => $email,
                'poste'         => $poste,
                'salaire'       => $salaire,
                'date_embauche' => $date_embauche,
                'id'            => $id
            ])) {
                header('Location: gestion_employes.php');
                exit;
            } else {
                $error = "Erreur lors de la mise à jour de l'employé.";
            }
        } else {
            $error = "Tous les champs sont obligatoires.";
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Modifier un Employé</title>
        <?php echo $css; ?>
    </head>
    <body>
        <div class="container">
            <h1>Modifier un Employé</h1>
            <?php if ($error): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="post" action="gestion_employes.php?action=edit&id=<?php echo $id; ?>">
                <label>Nom :</label>
                <input type="text" name="nom" value="<?php echo htmlspecialchars($employee['nom']); ?>" required>
                
                <label>Prénom :</label>
                <input type="text" name="prenom" value="<?php echo htmlspecialchars($employee['prenom']); ?>" required>
                
                <label>Email :</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                
                <label>Poste :</label>
                <input type="text" name="poste" value="<?php echo htmlspecialchars($employee['poste']); ?>" required>
                
                <label>Salaire :</label>
                <input type="number" name="salaire" value="<?php echo htmlspecialchars($employee['salaire']); ?>" required step="0.01">
                
                <label>Date d'embauche :</label>
                <input type="date" name="date_embauche" value="<?php echo htmlspecialchars($employee['date_embauche']); ?>" required>
                
                <button type="submit">Mettre à jour</button>
            </form>
            <p style="text-align: center;"><a href="gestion_employes.php">Retour à la liste</a></p>
        </div>
    </body>
    </html>
    <?php
    exit;
} elseif ($action === 'delete') {
    // ACTION : SUPPRIMER UN EMPLOYÉ
    if (!isset($_GET['id'])) {
        header('Location: gestion_employes.php');
        exit;
    }
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = :id");
    $stmt->execute(['id' => $id]);
    header('Location: gestion_employes.php');
    exit;
} else {
    // ACTION : AFFICHAGE DE LA LISTE DES EMPLOYÉS avec recherche globale
    $searchTerm = trim($_GET['search'] ?? '');
    if ($searchTerm !== '') {
        $stmt = $pdo->prepare("SELECT * FROM employees WHERE nom LIKE :search OR prenom LIKE :search OR email LIKE :search OR poste LIKE :search");
        $stmt->execute(['search' => "%$searchTerm%"]);
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $pdo->query("SELECT * FROM employees");
        $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Gestion des Employés</title>
        <?php echo $css; ?>
    </head>
    <body>
        <div class="container">
            <h1>Liste des Employés</h1>
            <div class="search-form">
                <form method="get" action="gestion_employes.php">
                    <input type="text" name="search" placeholder="Recherche globale" value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit">Rechercher</button>
                </form>
            </div>
            <p style="text-align: center;"><a href="gestion_employes.php?action=add">Ajouter un employé</a></p>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Poste</th>
                        <th>Salaire</th>
                        <th>Date d'embauche</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($employees)): ?>
                        <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($employee['id']); ?></td>
                                <td><?php echo htmlspecialchars($employee['nom']); ?></td>
                                <td><?php echo htmlspecialchars($employee['prenom']); ?></td>
                                <td><?php echo htmlspecialchars($employee['email']); ?></td>
                                <td><?php echo htmlspecialchars($employee['poste']); ?></td>
                                <td><?php echo htmlspecialchars($employee['salaire']); ?></td>
                                <td><?php echo htmlspecialchars($employee['date_embauche']); ?></td>
                                <td>
                                    <a href="gestion_employes.php?action=edit&id=<?php echo $employee['id']; ?>">Modifier</a> |
                                    <a href="gestion_employes.php?action=delete&id=<?php echo $employee['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" style="text-align: center;">Aucun employé trouvé.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <p style="text-align: center;"><a href="logout.php">Se déconnecter</a></p>
        </div>
    </body>
    </html>
    <?php
}
?>
