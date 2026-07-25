<?php
session_start();
include "config.php";
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if($stmt->fetch()) {
        $erreur = "Cet email est déjà utilisé.";}
    else{
        if(strlen($mot_de_passe) < 6) {
        $erreur = "Le mot de passe doit contenir au moins 6 caractères.";
        } else {
        
            $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");

            $stmt->execute([$nom, $email, $hash, 'client']);
            $succes = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Inscription</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
<link rel="icon" type="image/jpg" href="images/Favicon.jpg">

</head>
<body>
<header class="header">
    <h1>Bijoux Élégance</h1>
    <h1>Créer un compte</h1>
</header> 
<main>
<?php 
if(isset($erreur)) echo "<p style='color:red;'>$erreur</p>";
if(isset($succes)) echo "<p style='color:green;'>$succes</p>";
?>

<form method="post">
    <label>Nom :</label><br>
    <input type="text" name="nom" required><br><br>
    <label>Email :</label><br>
    <input type="email" name="email" required><br><br>
    <label>Mot de passe :</label><br>
    <input type="password" name="mot_de_passe" required><br><br>
    <button class="btn" type="submit">Créer mon compte</button>
</form>

<p><a class="btn" href="connexion.php">Retour à la connexion</a></p>
</main>
<footer>
  <p>&copy; 2026 Bijoux Élégance</p>
</footer>

</body>
</html>
