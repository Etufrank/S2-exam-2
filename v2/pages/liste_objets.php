<?php
session_start();
include("../inc/fonction.php");
ini_set('display_errors', 1); error_reporting(E_ALL);

$filtre_categorie = $_GET["categorie"] ?? "";
$filtre_nom = $_GET["nom"] ?? "";
$filtre_dispo = isset($_GET["disponible"]);

$res = getObjets2($filtre_categorie, $filtre_nom, $filtre_dispo);
$cat_res = getCategories();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des objets</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/liste.css">
</head>
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

    <h1 class="mb-4">Liste des objets</h1>

    <form method="GET" class="mb-4 d-flex gap-3 flex-wrap align-items-end">
        <div>
            <label for="categorie">Catégorie :</label>
            <select name="categorie" id="categorie" class="form-select">
                <option value="">-- Toutes les catégories --</option>
                <?php while ($cat = mysqli_fetch_assoc($cat_res)): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= ($filtre_categorie == $cat['id_categorie']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nom_categorie']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label for="nom">Nom de l’objet :</label>
            <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($filtre_nom) ?>" class="form-control">
        </div>

        <div class="form-check mt-4">
            <input type="checkbox" name="disponible" id="disponible" class="form-check-input" <?= $filtre_dispo ? "checked" : "" ?>>
            <label class="form-check-label" for="disponible">Disponible uniquement</label>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </div>
    </form>

    <section>
        <?php while ($obj = mysqli_fetch_assoc($res)): ?>
            <a href="details_objets.php?id=<?= $obj["id_objet"] ?>" class="objet-link text-decoration-none text-dark">
                <div class="objet border p-3 mb-3 rounded">
                    <h2><?= htmlspecialchars($obj["nom_objet"]) ?></h2>
                    <p>Catégorie : <?= htmlspecialchars($obj["nom_categorie"]) ?></p>

                    <?php 
                        $images_res = getImagesObjet($obj["id_objet"]);
                        if ($images_res && mysqli_num_rows($images_res) > 0) {
                            $image = mysqli_fetch_assoc($images_res);
                            echo '<img src="../uploads/' . htmlspecialchars($image['nom_image']) . '" alt="Image de ' . htmlspecialchars($obj["nom_objet"]) . '" style="max-width:200px; display:block; margin-bottom:10px;">';
                        } else {
                            echo '<img src="../assets/default.png" alt="Image par défaut" style="max-width:200px; display:block; margin-bottom:10px;">';
                        }
                    ?>

                    <?php if ($obj["date_retour"]): ?>
                        <p class="emprunte text-danger">Emprunté jusqu'au : <?= $obj["date_retour"] ?></p>
                    <?php else: ?>
                        <p class="dispo text-success">Disponible</p>
                    <?php endif; ?>
                </div>
            </a>
        <?php endwhile; ?>
    </section>

</body>
</html>
