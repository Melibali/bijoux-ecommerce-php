<?php
session_start();
include "config.php";

include "auth.php";
verifierAdmin();

if(isset($_POST['id'])){
    $id = (int)$_POST['id'];

    $stmt = $conn->prepare("
    UPDATE commandes 
    SET statut = 'validee' 
    WHERE id = ?
    ");

    $stmt->execute([$id]);

    header("Location: valider_cmd_admin.php");
    exit;
}

$stmt = $conn->prepare("
SELECT c.id, c.date_commande, c.total, c.statut, u.nom
FROM commandes c
JOIN utilisateurs u ON c.utilisateur_id = u.id
ORDER BY c.date_commande DESC
");

$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion commandes</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
<link rel="icon" type="image/jpg" href="images/Favicon.jpg">


</head>
<body>

<header class="header">
    <h1>Gestion des commandes</h1>
</header>

<main>

<table border="1">
<tr>
    <th>ID</th>
    <th>Client</th>
    <th>Date</th>
    <th>Total</th>
    <th>Statut</th>
    <th>Action</th>
</tr>

<?php foreach($commandes as $c): ?>
<tr>
    <td><?php echo $c['id']; ?></td>
    <td><?php echo htmlspecialchars($c['nom']); ?></td>
    <td><?php echo $c['date_commande']; ?></td>
    <td><?php echo $c['total']; ?> €</td>
    <td><?php echo $c['statut']; ?></td>
    <td>
        <?php if($c['statut'] == 'en cours'): ?>
        <form method="POST" style="margin:0;">
            <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
            <button type="submit" class="btn">Valider</button>
        </form>
        <?php else: ?>
            Validée
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>

<br>
<a href="admin_dashboard.php" class="btn">Retour</a>
</main>

<footer>
  <p>&copy; 2026 Bijoux Élégance</p>
</footer>

</body>
</html>