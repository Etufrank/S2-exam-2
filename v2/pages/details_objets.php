<?php
session_start();
include("../inc/fonction.php");
ini_set('display_errors', 1); error_reporting(E_ALL);

if (!isset($_GET["id"])) {
    die("Objet non spécifié.");
}

$id_objet = intval($_GET["id"]);
$bdd = connexionBDD();

// Récupérer l'objet avec sa catégorie et le membre
$sql = "SELECT o.*, c.nom_categorie, m.nom AS nom_membre 
        FROM S2_objet o
        JOIN S2_categorie_objet c ON o.id_categorie = c.id_categorie
        JOIN S2_membre m ON o.id_membre = m.id_membre
        WHERE o.id_objet = $id_objet";
$res_objet = mysqli_query($bdd, $sql);
$obj = mysqli_fetch_assoc($res_objet);

if (!$obj) {
    die("Objet introuvable.");
}


$images_res = getImagesObjet($id_objet);
$images = [];
while ($img = mysqli_fetch_assoc($images_res)) {
    $images[] = $img['nom_image'];
}

/
$emprunts_res = mysqli_query($bdd, "SELECT * FROM S2_emprunt WHERE id_objet = $id_objet ORDER BY date_emprunt DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de l'objet</title>
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

    <a href="liste_objets.php" class="btn btn-secondary mb-3">← Retour à la liste</a>

    <div class="card p-4 shadow-sm">
        <h2><?= htmlspecialchars($obj['nom_objet']) ?></h2>
        <p><strong>Catégorie :</strong> <?= htmlspecialchars($obj['nom_categorie']) ?></p>
        <p><strong>Ajouté par :</strong> <?= htmlspecialchars($obj['nom_membre']) ?></p>

        <?php if (!empty($images)): ?>
            <img src="../uploads/<?= htmlspecialchars($images[0]) ?>" alt="Image principale" class="img-fluid mb-3" style="max-width:300px;">
        <?php else: ?>
            <img src="../assets/images/default.png" alt="Image par défaut" class="img-fluid mb-3" style="max-width:300px;">
        <?php endif; ?>

        <?php if (count($images) > 1): ?>
            <h5>Autres images :</h5>
            <div class="d-flex flex-wrap gap-2">
                <?php foreach (array_slice($images, 1) as $img): ?>
                    <img src="../uploads/<?= htmlspecialchars($img) ?>" alt="Autre image" style="width:150px;">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <hr>
        <h5>Historique des emprunts</h5>
        <?php if (mysqli_num_rows($emprunts_res) > 0): ?>
            <ul>
                <?php while ($e = mysqli_fetch_assoc($emprunts_res)): ?>
                    <li>Du <?= $e['date_emprunt'] ?> au <?= $e['date_retour'] ?? '---' ?></li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Aucun emprunt pour cet objet.</p>
        <?php endif; ?>
    </div>

</body>
</html>
