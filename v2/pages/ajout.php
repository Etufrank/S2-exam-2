<?php
session_start();
include("../inc/fonction.php");

$categories = getCategories();


$message = "";
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un nouvel objet</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/ajout.css">
</head>
<body class="container mt-5">

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

    <h2 class="mb-4">Ajouter un nouvel objet</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" action="../inc/traitajout.php" class="card p-4 shadow">
        <div class="mb-3">
            <label class="form-label">Nom de l'objet</label>
            <input type="text" name="nom_objet" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Catégorie</label>
            <select name="id_categorie" class="form-select" required>
                <option value="">-- Choisir une catégorie --</option>
                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?= $cat['id_categorie'] ?>"><?= htmlspecialchars($cat['nom_categorie']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Images (la première est l'image principale)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="liste_objets.php" class="btn btn-link">← Retour à la liste</a>
    </form>

</body>
</html>
