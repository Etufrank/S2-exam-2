<?php
session_start();
include("../inc/fonction.php");

$filtre = isset($_GET["categorie"]) ? $_GET["categorie"] : "";

$res = getObjets($filtre);
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
<body>

    <h1>Liste des objets</h1>

    <form method="GET">
        <label for="categorie">Filtrer par categorie :</label>
        <select name="categorie" id="categorie">
            <option value="">-- Toutes les categories --</option>
            <?php while ($cat = mysqli_fetch_assoc($cat_res)): ?>
                <option value="<?= $cat['id_categorie'] ?>" <?= ($filtre == $cat['id_categorie']) ? 'selected' : '' ?>>
                    <?= ($cat['nom_categorie']) ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Filtrer</button>
    </form>

    <section>
        <?php while ($obj = mysqli_fetch_assoc($res)): ?>
            <div class="objet">
                <h2><?= ($obj["nom_objet"]) ?></h2>
                <p>Categorie : <?= ($obj["nom_categorie"]) ?></p>

                <?php 
                    $images_res = getImagesObjet($obj["id_objet"]);
                    if ($images_res && mysqli_num_rows($images_res) > 0) {
                        $image = mysqli_fetch_assoc($images_res);
                        echo '<img src="../uploads/' . htmlspecialchars($image['nom_image']) . '" alt="Image de ' . htmlspecialchars($obj["nom_objet"]) . '" style="max-width:200px; display:block; margin-bottom:10px;">';
                    } else {
                        echo '<p>Aucune image disponible</p>';
                    }
                ?>

                <?php if ($obj["date_retour"]): ?>
                    <p class="emprunte">Emprunté jusqu'au : <?= ($obj["date_retour"]) ?></p>
                <?php else: ?>
                    <p class="dispo">Disponible</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </section>

</body>
</html>
