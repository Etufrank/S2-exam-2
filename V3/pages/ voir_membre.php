<?php
session_start();
include("../inc/fonction.php");

if (!isset($_SESSION["id_membre"])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID membre invalide.";
    exit();
}

$id_membre = intval($_GET['id']);

$bdd = connexionBDD();

$sql = "SELECT id_membre, nom, email, ville, genre, date_naissance, image_profil FROM S2_membre WHERE id_membre = $id_membre";
$res = mysqli_query($bdd, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    echo "Membre introuvable.";
    exit();
}

$membre = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Fiche Membre - <?= ($membre['nom']) ?></title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="../index.php">S2 Projet</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarS2" aria-controls="navbarS2" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarS2">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="liste_objets.php">Liste des objets</a></li>
        <li class="nav-item"><a class="nav-link" href="ajout.php">Ajouter un objet</a></li>
        <li class="nav-item"><a class="nav-link" href="fiche_membre.php">Mon profil</a></li>
        <li class="nav-item"><a class="nav-link" href="chercher_membre.php">Chercher Un Membre</a></li>
        <li class="nav-item"><a class="nav-link" href="../index.php">Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    <h2>Fiche du membre : <?= ($membre['nom']) ?></h2>

    <div class="row mb-4">
        <div class="col-md-4">
            <?php if (!empty($membre['image_profil']) && file_exists("../uploads/" . $membre['image_profil'])): ?>
                <img src="../uploads/<?= ($membre['image_profil']) ?>" alt="Photo de <?= ($membre['nom']) ?>" class="img-fluid rounded">
            <?php else: ?>
                <img src="../assets/images/default-profile.png" alt="Image par défaut" class="img-fluid rounded">
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <ul class="list-group">
                <li class="list-group-item"><strong>Nom :</strong> <?= ($membre['nom']) ?></li>
                <li class="list-group-item"><strong>Email :</strong> <?= ($membre['email']) ?></li>
                <li class="list-group-item"><strong>Ville :</strong> <?= ($membre['ville']) ?></li>
                <li class="list-group-item"><strong>Genre :</strong> <?= ($membre['genre']) ?></li>
                <li class="list-group-item"><strong>Date de naissance :</strong> <?= ($membre['date_naissance']) ?></li>
            </ul>
        </div>
    </div>

</div>

<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
