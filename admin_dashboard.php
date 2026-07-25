<?php
include "auth.php";
verifierAdmin();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Tableau de bord Admin</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
<link rel="icon" type="image/jpg" href="images/Favicon.jpg">
</head>
<body>
<header class="header">
    <h1>Bienvenue ! <?php echo htmlspecialchars($_SESSION['nom']); ?> Admin</h1>
<!--htmlspecialchars ici pour empecher d’injecter du code HTML-->

</header>
<main class="main-admin">  

<div class="admin-actions">
    <a class="btn" href="gerer_produit.php">Gérer les produits</a>
    <a class="btn" href="gerer_utilisateurs.php">Gérer les utilisateurs</a>
    <a class="btn" href="valider_cmd_admin.php">Gérer les commandes</a>
    <a class="btn" href="deconnexion.php">Se déconnecter</a></div>

</main>

<footer>
  <p>&copy; 2026 Bijoux Élégance</p>
</footer>

</body>
</html>