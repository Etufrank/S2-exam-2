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

    <h2 class="mb-4">Ajouter un nouvel objet</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" action="traitajout.php" class="card p-4 shadow">
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
