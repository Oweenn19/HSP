<?php
session_start();

// Vérifie si l'utilisateur a soumis le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Tableau pour les erreurs
    $errors = array();

    // Connexion base de données
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'inscription');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    try {
        $bdd = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASSWORD);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Activer les exceptions PDO
    } catch (PDOException $e) {
        $errors[] = "Erreur de connexion à la base de données : " . $e->getMessage();
    }

    // Vérifie si l'adresse e-mail et le mot de passe sont non vides
    if (empty($email)) {
        $errors[] = "L'adresse e-mail est requise";
    }
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis";
    }

    // Vérifie si l'adresse e-mail existe déjà dans la base de données
    if (empty($errors)) {
        $req = $bdd->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $req->execute(['email' => $email]);
        $user = $req->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Si l'utilisateur existe et que le mot de passe est correct, crée une variable de session pour stocker son prénom
            $_SESSION["user"] = $user["prenom"];
            header("Location: reservation_de_vol.html");
            exit;
        } else {
            $errors[] = "Adresse e-mail ou mot de passe incorrect";
        }
    }

    // Afficher les erreurs
    if (!empty($errors)) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}
?>