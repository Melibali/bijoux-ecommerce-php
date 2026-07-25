<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "config.php";
include "auth.php";
verifierClient();
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    die("Votre panier est vide.");
}
$utilisateur_id = $_SESSION['user_id'];
$panier = $_SESSION['panier'];
$total = 0;

foreach ($panier as $item) {
    $stmtStock = $conn->prepare("SELECT stock FROM produits WHERE id = ?");
    $stmtStock->execute([$item['id']]);
    $produit = $stmtStock->fetch(PDO::FETCH_ASSOC);
    if (!$produit) {
        die("Produit introuvable : " . htmlspecialchars($item['nom']));
    }
    $quantite = (int)$item['quantite'];
    $stock = (int)$produit['stock'];
    if ($quantite <= 0) {
        die("Quantité invalide pour le produit : " . htmlspecialchars($item['nom']));
    }
    if ($quantite > $stock) {
        die("Quantité demandée trop élevée pour le produit : " . htmlspecialchars($item['nom']) . " (stock disponible : $stock)");
    }
    $total += $item['prix'] * $quantite;
}

$stmt = $conn->prepare("INSERT INTO commandes (utilisateur_id, total,statut) VALUES (?, ?,?)");
$stmt->execute([$utilisateur_id, $total, 'en cours']);
$commande_id = $conn->lastInsertId();

$stmtDetails = $conn->prepare("
    INSERT INTO details_commandes
    (commande_id, produit_id, quantite, prix_unitaire)
    VALUES (?, ?, ?, ?)
");

$stmtUpdateStock = $conn->prepare("UPDATE produits SET stock = stock - ? WHERE id = ?");
foreach ($panier as $item) {
    $quantite = (int)$item['quantite'];
    $stmtDetails->execute([
        $commande_id,
        $item['id'],
        $quantite,
        $item['prix']
    ]);
    $stmtUpdateStock->execute([
        $quantite,
        $item['id']
    ]);
}
unset($_SESSION['panier']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Commande validée</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
<link rel="icon" type="image/jpg" href="images/Favicon.jpg">

</head>
<body>

<header class="header-image">
  <h1>Bijoux Élégance</h1>
</header>

<main class="contact-container">
    <h2 class="contact-title">Commande Validée</h2>
    
    <div class="contact-card" style="text-align: center;">
        <div style="font-size: 3rem; margin-bottom: 10px;">✨</div>
        
        <h3 style="font-family: 'Cinzel', serif; color: #884646;">Succès !</h3>
        
        <p>Félicitations</p>
        <p>Votre commande a bien été enregistrée.</p>
        
        <div style="margin: 20px 0; font-weight: bold; color: #884646; font-size: 1.3rem;">
            Total payé : <?php echo number_format($total, 2); ?> €
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="boutique.php" class="btn">Retour à la boutique</a>
            <a href="test.html" class="btn">Retour à l'accueil</a>
        </div>
    </div>
</main>




<footer class="footer-image">
  <p>© 2026 Bijoux Élégance</p>
</footer>

</body>
</html>
