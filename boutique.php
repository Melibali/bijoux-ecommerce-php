<?php
session_start();
error_reporting(E_ALL);

include "config.php";
include "auth.php";
verifierClient();
$query = "SELECT * FROM produits WHERE 1";
$params = [];

if(!empty($_GET['q'])){
    $query .= " AND nom LIKE ?";
    $params[] = "%".$_GET['q']."%";
}
if(!empty($_GET['categorie'])){
    $query .= " AND categorie = ?";
    $params[] = $_GET['categorie'];
}
$query .= " ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->execute($params);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Boutique</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
<link rel="icon" type="image/jpg" href="images/Favicon.jpg">
</head>

<body>
<header class="header">
    <h1>Bijoux Élégance</h1>

    <div class="liens_header">
        <a href="accueil.html">Retour à l'accueil</a>
        <a href="commande.php">Commandes</a>
        <a href="deconnexion.php">Déconnexion</a>
    </div>
</header>

<form method="GET" action="" class="recherche">

    <input  type="text" name="q" placeholder="Rechercher un bijou..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">

    <select name="categorie">
        <option value="">Toutes catégories</option>
        <option value="collier">Collier</option>
        <option value="bracelet">Bracelet</option>
        <option value="bague">Bague</option>
        <option value="boucle">Boucle</option>
        <option value="parure">Parure</option>
    </select>
    <button type="submit" class="btn">Rechercher</button>

</form>

<div class="produits">
<?php if(count($produits) > 0): ?>
    <?php foreach($produits as $p): ?>
        <div class="produit">
            <img src="images/<?php echo htmlspecialchars($p['image']); ?>" 
                 alt="<?php echo htmlspecialchars($p['nom']); ?>">
            <h3><?php echo htmlspecialchars($p['nom']); ?></h3>
            <p><?php echo htmlspecialchars($p['description']); ?></p>
            <p><strong>Prix :</strong> <?php echo number_format($p['prix'], 2); ?> €</p>
            <p><strong>Stock :</strong> <?php echo $p['stock']; ?></p>

            <?php if ($p['stock'] > 0): ?>    
                <form method="POST" action="ajouter_panier.php">       
                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                    <input type="number" name="quantite" min="1" max="<?php echo $p['stock']; ?>" value="1" required><br><br>
                    <button class="btn" type="submit">Ajouter au panier</button>
                </form>
            <?php else: ?>
                <p style="color:red;"><strong>Rupture de stock</strong></p>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun produit disponible.</p>
<?php endif; ?>
</div>

<div class="liens-bas-page">
    <a href="panier.php">Voir mon panier</a>
    <span style="color: #ebc5ab;">|</span>
    <a href="commande.php">Mes commandes</a>
</div>

<script>
const produits = document.querySelectorAll('.produit');

function revealOnScroll() {
  const trigger = window.innerHeight * 0.9;

  produits.forEach((prod, i) => {
    if (prod.getBoundingClientRect().top < trigger) {
      setTimeout(() => prod.classList.add('visible'), i * 150);
    }
  });
}

addEventListener('scroll', revealOnScroll);
addEventListener('load', revealOnScroll);


</script>

<footer>
  <p>&copy; 2026 Bijoux Élégance</p>
</footer>

</body>
</html>
