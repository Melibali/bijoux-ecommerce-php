<?php
session_start();
$message = "";
include "config.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $mdp = $_POST["mdp"];

    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role']; 
        $_SESSION['nom'] = $user['nom'];

        
        if($user['role'] === 'admin'){
            header("Location: admin_dashboard.php"); 
        } else {
            header("Location: accueil.html"); 
        }
        exit;
    } else {
        $message = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion</title>
<link rel="stylesheet" href="style.css">
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600&display=swap" rel="stylesheet">
<link rel="icon" type="image/jpg" href="images/Favicon.jpg">

</head>
<body>
   <header class="header">
        <h1>Bijoux Élégance</h1>
        <h1>Connexion</h1>
    </header> 
<main class="cnx">
<p style="color:red;"><?php echo $message; ?></p>
<form method="POST" action="connexion.php">
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="mdp" placeholder="Mot de passe" required><br><br>
    <button class="btn" type="submit">Se connecter</button>
</form>

<p > Vous n'avez pas de compte ?</p><br>
<a class="btn" href="inscription.php">Créer un compte</a>
<br>
<p>Si vous êtes administrateur, utilisez votre compte admin pour vous connecter.</p>

</main>
<footer>
  <p>&copy; 2026 Bijoux Élégance</p>
</footer>


</body>
</html>
