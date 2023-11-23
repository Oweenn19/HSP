<?php

try {
    $bdd = new PDO('mysql:host=localhost;dbname=inscription;charset=utf8', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

if(isset($_POST['submit'])) {

    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $mot_de_passe2 = $_POST['mot_de_passe2'];

    $stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    
    if ($user) {
        echo "Désolé, cet e-mail est déjà utilisé.";
    } else {
        
        $requete = $bdd->prepare('INSERT INTO utilisateurs(nom, prenom, email, mot_de_passe) VALUES(:nom, :prenom, :email, :mot_de_passe)');
        $requete->execute(array(
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'mot_de_passe' => $mot_de_passe
        ));

        echo "Félicitations, vous vous êtes inscrit avec succès !";
    }
}

?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>Connexion à HSP</title>
    <link rel="stylesheet" type="text/css" href="connexion.css">
  </head>
  <body>

  <nav>
    <div class="logo"> <a href="navbar.html">HSP</a></div>
    <ul class="mobile-menu">
      <li><a href="connexion.html">Connexion</a></li>
      <li><a href="inscription.html">Inscription</a></li>
    </ul>
    <div class="burger">
      <div class="line1"></div>
      <div class="line2"></div>
      <div class="line3"></div>
    </div>
  </nav>

    <h1>Connexion à HSP</h1>
    <form method="POST" action="connexion.php">
      <label for="email">Adresse e-mail :</label>
      <input type="email" id="email" name="email" required><br>

      <label for="password">Mot de passe :</label>
      <input type="password" id="password" name="password" required><br>

      <button type="submit">Se connecter</button>
    </form>
  </body>
</html>