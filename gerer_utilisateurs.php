<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "config.php";
include "auth.php";
verifierAdmin();

if(isset($_POST['supprimer'])){
    $id = (int)$_POST['supprimer'];
    

    try {
        $stmt = $conn->prepare("
            DELETE dc 
            FROM details_commandes dc
            JOIN commandes c ON dc.commande_id = c.id
            WHERE c.utilisateur_id = ?
        ");
        $stmt->execute([$id]);

        $stmt = $conn->prepare("DELETE FROM commandes WHERE utilisateur_id = ?");
        $stmt->execute([$id]);

        $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: gerer_utilisateurs.php");
        exit;

    } catch(PDOException $e) {
        die("Erreur lors de la suppression : " . $e->getMessage());
    }
}

$stmt = $conn->query("SELECT * FROM utilisateurs ORDER BY id DESC");
$utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gérer les utilisateurs</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
<link rel="icon" type="image/jpg" href="images/Favicon.jpg">
</head>

<body>

<header class="header">
 <h1>Gérer les utilisateurs</h1>
</header>

<main>
<a href="admin_dashboard.php" class="btn btn-retour">← Retour au tableau admin</a>

<table class="table-users">
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Email</th>
    <th>Rôle</th>
    <th>Action</th>
</tr>

<?php foreach($utilisateurs as $u): ?>
<tr>
    <td><?php echo $u['id']; ?></td>
    <td><?php echo htmlspecialchars($u['nom']); ?></td>
    <td><?php echo htmlspecialchars($u['email']); ?></td>
    <td><?php echo htmlspecialchars($u['role']); ?></td>
    <td>
        <form method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');">
            <input type="hidden" name="supprimer" value="<?php echo $u['id']; ?>">
            <button type="submit" class="btn btn-supprimer">Supprimer</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>

</table>
</main>
<footer>
  <p>&copy; 2026 Bijoux Élégance</p>
</footer>
</body>
</html>
