<?php
session_start();
include("../inc/fonction.php");
ini_set('display_errors', 1); error_reporting(E_ALL);

if (!isset($_SESSION["id_membre"])) {
    header("Location: ../index.php");
    exit();
}

$id_membre = $_SESSION["id_membre"];
$infos = getInfosMembre($id_membre);
$objets = getObjetsParCategorieDuMembre($id_membre);


$groupes = [];
while ($obj = mysqli_fetch_assoc($objets)) {
    $cat = $obj["nom_categorie"];
    $groupes[$cat][] = $obj["nom_objet"];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Membre</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="container mt-4">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand" href="../index.php">S2 Projet</a>
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
          <a class="nav-link" href="../index.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
    <h2>Fiche Membre</h2>
    <p><strong>Nom :</strong> <?= htmlspecialchars($infos["nom"]) ?></p>
    <p><strong>Email :</strong> <?= htmlspecialchars($infos["email"]) ?></p>
    <p><strong>Ville :</strong> <?= htmlspecialchars($infos["ville"]) ?></p>

    <h3>Objets par catégorie</h3>
    <?php if (!empty($groupes)): ?>
        <?php foreach ($groupes as $categorie => $objets): ?>
            <div class="mb-3">
                <h5><?= htmlspecialchars($categorie) ?></h5>
                <ul>
                    <?php foreach ($objets as $nom_objet): ?>
                        <li><?= htmlspecialchars($nom_objet) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucun objet enregistré.</p>
    <?php endif; ?>
</body>
</html>
