<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include "config.php";

include "auth.php";
verifierAdmin();

if(isset($_POST['ajouter'])){

    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = (float)$_POST['prix'];
    $stock = (int)$_POST['stock'];
    $image = trim($_POST['image']);
    $categorie = trim($_POST['categorie']);

    $stmt = $conn->prepare("SELECT id FROM produits WHERE image = ?");
    $stmt->execute([$image]);
    $produitExiste = $stmt->fetch(PDO::FETCH_ASSOC);

    if($produitExiste){

        $stmt = $conn->prepare("UPDATE produits SET stock = stock + ? WHERE id = ?");
        $stmt->execute([$stock, $produitExiste['id']]);

        $message = "Produit déjà existant : stock mis à jour.";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO produits (nom, description, prix, stock, image, categorie)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([$nom, $description, $prix, $stock, $image, $categorie]);

        $message = "Produit ajouté avec succès !";
    }
}

if(isset($_POST['supprimer'])){
    $id = (int)$_POST['supprimer'];
    $stmt = $conn->prepare("SELECT * FROM details_commandes WHERE produit_id = ?");
    $stmt->execute([$id]);
    if($stmt->fetch()){
        $message = "Impossible de supprimer : produit utilisé dans une commande ";
    } else {
        $stmt = $conn->prepare("DELETE FROM produits WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Produit supprimé avec succès ";
    }
}
    $query = "SELECT * FROM produits WHERE 1";
    $params = [];


    $query .= " ORDER BY id DESC";

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<title>Gérer les produits</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
<link rel="icon" type="image/jpg" href="images/Favicon.jpg">
</head>
<body>
<header class="header">
 <h1>Gestion des produits</h1>
</header>
<main>
<a href="admin_dashboard.php" class="btn">← Retour au tableau de bord</a>

<?php if(isset($message)) echo "<div class='message'>$message</div>"; ?>

<form method="POST" action="">
    <input type="text" name="nom" placeholder="Nom du produit" required>
    <input type="text" name="description" placeholder="Description" required>
    <input type="number" step="0.01" name="prix" placeholder="Prix" required>
    <input type="number" name="stock" placeholder="Stock" required>
    <input type="text" name="image" placeholder="Nom de l'image (ex: produit.jpg)" required>
    <input type="text" name="categorie" placeholder="Catégorie" required>

    <button type="submit" name="ajouter">Ajouter</button>
</form>
<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Description</th>
        <th>Prix</th>
        <th>Stock</th>
        <th>Image</th>
        <th>Actions</th>
    </tr>
    <?php foreach($produits as $p): ?>
    <tr>
        <td><?php echo $p['id']; ?></td>
        <td><?php echo htmlspecialchars($p['nom']); ?></td>
        <td><?php echo htmlspecialchars($p['description']); ?></td>
        <td><?php echo number_format($p['prix'],2); ?> €</td>
        <td><?php echo $p['stock']; ?></td>
        <td><?php echo htmlspecialchars($p['image']); ?></td>
        <td>
            <form method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce produit ?');">
                <input type="hidden" name="supprimer" value="<?php echo $p['id']; ?>">
                <button type="submit" class="btn-supprimer">Supprimer</button>
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
