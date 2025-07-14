<?php
session_start();
include("../inc/fonction.php");
ini_set('display_errors', 1); error_reporting(E_ALL);

if (!isset($_SESSION["id_membre"])) {
    header("Location: index.php");
    exit();
}

$bdd = connexionBDD();
$id_objet = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$message = "";

$sql = "SELECT * FROM S2_objet WHERE id_objet = $id_objet";
$res = mysqli_query($bdd, $sql);
if (!$res || mysqli_num_rows($res) == 0) {
    die("Objet non trouvé.");
}
$objet = mysqli_fetch_assoc($res);

$sql_check = "SELECT * FROM S2_emprunt WHERE id_objet = $id_objet AND date_retour IS NULL";
$check = mysqli_query($bdd, $sql_check);
if (mysqli_num_rows($check) > 0) {
    die("Cet objet est déjà emprunté.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["date_retour"])) {
    $date_retour = $_POST["date_retour"];
    $id_membre = $_SESSION["id_membre"];
    $date_emprunt = date("Y-m-d");

    $sql_insert = "INSERT INTO S2_emprunt (id_objet, id_membre, date_emprunt, date_retour)
                   VALUES ('$id_objet', '$id_membre', '$date_emprunt', '$date_retour')";
    if (mysqli_query($bdd, $sql_insert)) {
        header("Location: liste_objets.php?msg=emprunt_ok");
        exit();
    } else {
        $message = "<p style='color:red;'>Erreur : " . mysqli_error($bdd) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Emprunter : <?=($objet['nom_objet']) ?></title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Emprunter l’objet : <?=($objet['nom_objet']) ?></h2>
    <?= $message ?>

    <form method="POST">
        <div class="mb-3">
            <label for="date_retour" class="form-label">Date de retour prévue :</label>
            <input type="date" name="date_retour" id="date_retour" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Confirmer l’emprunt</button>
        <a href="liste_objets.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
