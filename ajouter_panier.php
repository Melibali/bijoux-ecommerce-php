<?php
include "config.php";
include "auth.php";
verifierClient();

if(!isset($_POST['id']) || !isset($_POST['quantite'])){
    die("Produit invalide");
}

$produit_id = (int)$_POST['id'];
$quantite = (int)$_POST['quantite'];

if($quantite <= 0){
    die("Quantité invalide");
}

$stmt = $conn->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$produit_id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$produit){
    die("Produit introuvable");
}

$stock = (int)$produit['stock'];
if($quantite > $stock){
    die("Stock insuffisant");
}

if(!isset($_SESSION['panier'])){
    $_SESSION['panier'] = [];
}

$trouve = false;

foreach($_SESSION['panier'] as &$item){
    if($item['id'] == $produit_id){
        $item['quantite'] += $quantite;
        $trouve = true;
        break;
    }
}

if(!$trouve){
    $_SESSION['panier'][] = [
        "id" => $produit['id'],
        "nom" => $produit['nom'],
        "prix" => $produit['prix'],
        "quantite" => $quantite
    ];
}

header("Location: boutique.php");
exit;
