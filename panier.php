<?php
session_start();
include "auth.php";
verifierClient();
if(isset($_GET['supprimer'])){
    $idSuppr = (int)$_GET['supprimer'];
    foreach($_SESSION['panier'] as $index => $item){
        if($item['id'] == $idSuppr){
            unset($_SESSION['panier'][$index]);
            break;
        }
    }
    $_SESSION['panier'] = array_values($_SESSION['panier']); 
    header("Location: panier.php");
    exit;
}
$panier = $_SESSION['panier'] ?? [];
$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Panier</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
<link rel="icon" type="image/jpg" href="images/Favicon.jpg">
</head>

<body>
<header class="header">
    <h1>Bijoux Élégance</h1>
<div class="liens_header">
    <nav class="nav">
        <a href="accueil.html">Retour à l'accueil</a>
        <a href="commande.php">Commandes</a>
        <a href="deconnexion.php">Déconnexion</a>
    </nav>
</div>
</header>
<main>
<h1>Votre panier</h1>

<?php if(empty($panier)): ?>
<p>Votre panier est vide</p>
<?php else: ?>

<table>
<tr>
<th>Produit</th>
<th>Prix</th>
<th>Quantité</th>
<th>Sous-total</th>
<th>Action</th>

</tr>

<?php foreach($panier as $id => $item): 
$sousTotal = $item['prix'] * $item['quantite'];
$total += $sousTotal;
?>

<tr>
<td><?php echo htmlspecialchars($item['nom']); ?></td>
<td><?php echo number_format($item['prix'],2); ?> €</td>
<td><?php echo $item['quantite']; ?></td>
<td><?php echo number_format($sousTotal,2); ?> €</td>
<td>
    <a class="btn-supprimer" 
       href="?supprimer=<?php echo $item['id']; ?>" 
       onclick="return confirm('Voulez-vous supprimer ce produit du panier ?');">
       Supprimer
    </a>
</td>
</tr>

<?php endforeach; ?>

<tr>
<td colspan="3"><strong>Total</strong></td>
<td><strong><?php echo number_format($total,2); ?> €</strong></td>
</tr>

</table>

<a class="btn" href="valider_commande.php">Passer commande</a>

<?php endif; ?>

<a class="btn" href="boutique.php">Retour boutique</a>
</main>

<footer>
  <p>&copy; 2026 Bijoux Élégance</p>
</footer>

</body>
</html>
