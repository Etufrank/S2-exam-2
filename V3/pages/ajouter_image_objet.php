<?php
session_start();
include("../inc/fonction.php");
$bdd = connexionBDD();
ini_set('display_errors', 1); error_reporting(E_ALL);

if (!isset($_SESSION["id_membre"])) {
    header("Location:index.php");
    exit();
}

$categorie = $_GET['categorie'] ?? "";
$message = "";

$cat_res = mysqli_query($bdd, "SELECT * FROM S2_categorie_objet");

$obj_res = false;
if ($categorie !== "") {
    $categorie = intval($categorie); 
    $sql = "SELECT id_objet, nom_objet FROM S2_objet WHERE id_categorie = $categorie";
    $obj_res = mysqli_query($bdd, $sql);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_objet"])) {
    $id_objet = intval($_POST["id_objet"]);

    if (!empty($_FILES["image"]["name"])) {
        $image_name = basename($_FILES["image"]["name"]);
        $tmp = $_FILES["image"]["tmp_name"];
        $dest = "../uploads/" . $image_name;

        if (move_uploaded_file($tmp, $dest)) {
            $stmt = mysqli_prepare($bdd, "INSERT INTO S2_images_objet (id_objet, nom_image) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt, "is", $id_objet, $image_name);
            if (mysqli_stmt_execute($stmt)) {
                $message = "<div class='alert alert-success'>Image ajoutee avec succès.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Erreur SQL.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>echec de l'envoi du fichier.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une image à un objet</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="index.php">S2 Projet</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarS2" aria-controls="navbarS2" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarS2">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="liste_objets.php">Liste des objets</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="ajout.php">Ajouter un objet</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="fiche_membre.php">Mon profil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="chercher_membre.php">Chercher Un Membre</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../index.php">Deconnexion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


<div class="container mt-4">
    <h2>Ajouter une image à un objet</h2>
    <?= $message ?>

    <form method="GET" class="mb-3">
        <label for="categorie">Choisir une categorie :</label>
        <select name="categorie" id="categorie" class="form-select" onchange="this.form.submit()">
            <option value="">-- Selectionner --</option>
            <?php while ($cat = mysqli_fetch_assoc($cat_res)): ?>
                <option value="<?= $cat['id_categorie'] ?>" <?= ($categorie == $cat['id_categorie']) ? "selected" : "" ?>>
                    <?= ($cat['nom_categorie']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if ($categorie && $obj_res && mysqli_num_rows($obj_res) > 0): ?>
        <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label for="id_objet">Choisir un objet :</label>
                <select name="id_objet" id="id_objet" class="form-select" required>
                    <?php while ($obj = mysqli_fetch_assoc($obj_res)): ?>
                        <option value="<?= $obj['id_objet'] ?>"><?= ($obj['nom_objet']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image">Choisir une image :</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-primary">Ajouter l'image</button>
        </form>
    <?php elseif ($categorie): ?>
        <div class="alert alert-warning">Aucun objet trouve pour cette categorie.</div>
    <?php endif; ?>

    <a href="liste_objets.php" class="btn btn-link mt-3">← Retour à la liste</a>
</div>
</body>
</html>
