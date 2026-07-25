<?php
session_start();
include "config.php";
include "auth.php";
verifierClient();
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM commandes WHERE utilisateur_id = ? ORDER BY date_commande DESC");
$stmt->execute([$user_id]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mes commandes</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
<link rel="icon" type="image/jpg" href="images/Favicon.jpg">


</head>
<body>
<header class="header-image">
  <h1>Bijoux Élégance</h1>
</header>
<main>
<h2>Mes commandes</h2>

<?php if(!$commandes): ?>
<p>Vous n'avez passé aucune commande pour le moment.</p>
<?php else: ?>

<?php foreach($commandes as $commande): 
    $stmtDetails = $conn->prepare("
        SELECT dc.quantite, dc.prix_unitaire, p.nom 
        FROM details_commandes dc
        JOIN produits p ON dc.produit_id = p.id
        WHERE dc.commande_id = ?
    ");
    $stmtDetails->execute([$commande['id']]);
    $details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="commande">
    <h3>
        Commande :<?php echo $commande['id']; ?> - 
        <?php echo date("d/m/Y H:i", strtotime($commande['date_commande'])); ?> - 
        Statut : <?php echo htmlspecialchars($commande['statut']); ?>
    </h3>
    <table class="table-commande">
        <tr>
            <th>Produit</th>
            <th>Prix unitaire</th>
            <th>Quantité</th>
            <th>Sous-total</th>
        </tr>
        <?php foreach($details as $item): 
            $sousTotal = $item['prix_unitaire'] * $item['quantite'];
        ?>
        <tr>
            <td><?php echo htmlspecialchars($item['nom']); ?></td>
            <td><?php echo number_format($item['prix_unitaire'], 2); ?> €</td>
            <td><?php echo $item['quantite']; ?></td>
            <td><?php echo number_format($sousTotal, 2); ?> €</td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="3"><strong>Total</strong></td>
            <td><strong><?php echo number_format($commande['total'], 2); ?> €</strong></td>
        </tr>
    </table>
</div>
<?php endforeach; ?>

<?php endif; ?>
<a href="boutique.php" class="btn">Retour à la boutique</a>
</main>



<footer>
  <p>© 2026 Bijoux Élégance</p>
</footer>

</body>
</html>
