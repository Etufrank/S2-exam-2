<?php
session_start();
include("fonction.php");
ini_set('display_errors', 1); error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $mdp = trim($_POST["mdp"]);

    $bdd = connexionBDD();
    $sql = "SELECT * FROM S2_membre WHERE email = '$email' AND mdp = '$mdp'";
    $res = mysqli_query($bdd, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);
        $_SESSION["id_membre"] = $user["id_membre"];
        header("Location: ../pages/liste_objets.php");
        exit();
    } else {
        echo "<p style='color:red;'>Email ou mot de passe incorrect</p>";
        echo "<p>Email saisi : $email</p>";
        echo "<p>Mot de passe saisi : $mdp</p>";
    }
}
?>
