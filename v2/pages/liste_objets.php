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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">S2 Projet</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarS2">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarS2">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="liste_objets.php"><i class="bi bi-box-seam"></i> Objets</a></li>
        <li class="nav-item"><a class="nav-link" href="ajout.php"><i class="bi bi-plus-circle"></i> Ajouter</a></li>
        <li class="nav-item"><a class="nav-link" href="fiche_membre.php"><i class="bi bi-person-circle"></i> Mon profil</a></li>
        <li class="nav-item"><a class="nav-link" href="chercher_membre.php"><i class="bi bi-search"></i> Membres</a></li>
        <li class="nav-item"><a class="nav-link" href="../index.php"><i class="bi bi-box-arrow-right"></i> Déconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<main class="container my-5">
    <h1 class="mb-4 text-center">📦 Liste des objets</h1>

    <form method="GET" class="row g-3 align-items-end mb-5 bg-light p-4 rounded shadow-sm">
        <div class="col-md-4">
            <label for="categorie" class="form-label">Catégorie :</label>
            <select name="categorie" id="categorie" class="form-select">
                <option value="">-- Toutes --</option>
                <?php while ($cat = mysqli_fetch_assoc($cat_res)): ?>
                    <option value="<?= $cat['id_categorie'] ?>" <?= ($filtre_categorie == $cat['id_categorie']) ? 'selected' : '' ?>>
                        <?= ($cat['nom_categorie']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="col-md-4">
            <label for="nom" class="form-label">Nom de l’objet :</label>
            <input type="text" name="nom" id="nom" class="form-control" value="<?= ($filtre_nom) ?>">
        </div>

        <div class="col-md-3">
            <div class="form-check mt-4">
                <input type="checkbox" name="disponible" id="disponible" class="form-check-input" <?= $filtre_dispo ? "checked" : "" ?>>
                <label class="form-check-label" for="disponible">Disponibles uniquement</label>
            </div>
        </div>

        <div class="col-md-1 text-end">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
        </div>
    </form>

    <div class="row">
        <?php while ($obj = mysqli_fetch_assoc($res)): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <?php 
                        $images_res = getImagesObjet($obj["id_objet"]);
                        if ($images_res && mysqli_num_rows($images_res) > 0) {
                            $image = mysqli_fetch_assoc($images_res);
                            echo '<img src="../uploads/' . ($image['nom_image']) . '" class="card-img-top" alt="Image">';
                        } else {
                            echo '<img src="../assets/default.png" class="card-img-top" alt="Image par défaut">';
                        }
                    ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= ($obj["nom_objet"]) ?></h5>
                        <p class="card-text"><i class="bi bi-tag"></i> Catégorie : <?= ($obj["nom_categorie"]) ?></p>

                        <?php if ($obj["date_retour"]): ?>
                            <p class="text-danger"><i class="bi bi-x-circle"></i> Emprunté jusqu'au : <?= $obj["date_retour"] ?></p>
                        <?php else: ?>
                            <p class="text-success"><i class="bi bi-check-circle"></i> Disponible</p>
                        <?php endif; ?>

                        <a href="details_objets.php?id=<?= $obj["id_objet"] ?>" class="btn btn-outline-primary w-100 mt-2">Voir détails</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

</body>
</html>
