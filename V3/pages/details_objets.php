<?php
session_start();
include("../inc/fonction.php");
ini_set('display_errors', 1);
error_reporting(E_ALL);

$bdd = connexionBDD();

if (!isset($_SESSION["id_membre"])) {
    header("Location: ../index.php");
    exit();
}

$id_objet = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_objet <= 0) {
    echo "Objet non valide.";
    exit();
}

$sql_objet = "
    SELECT o.*, c.nom_categorie, m.nom AS nom_membre
    FROM S2_objet o
    LEFT JOIN S2_categorie_objet c ON o.id_categorie = c.id_categorie
    LEFT JOIN S2_membre m ON o.id_membre = m.id_membre
    WHERE o.id_objet = $id_objet
";
$res_objet = mysqli_query($bdd, $sql_objet);
if (!$res_objet) {
    die("Erreur SQL (objet) : " . mysqli_error($bdd));
}
if (mysqli_num_rows($res_objet) == 0) {
    echo "Objet non trouve.";
    exit();
}
$objet = mysqli_fetch_assoc($res_objet);
$sql_images = "SELECT * FROM S2_images_objet WHERE id_objet = $id_objet ORDER BY id_image ASC";
$res_images = mysqli_query($bdd, $sql_images);
if (!$res_images) {
    die("Erreur SQL (images) : " . mysqli_error($bdd));
}

$sql_emprunts = "
    SELECT e.date_emprunt, e.date_retour, m.nom AS emprunteur
    FROM S2_emprunt e
    LEFT JOIN S2_membre m ON e.id_membre = m.id_membre
    WHERE e.id_objet = $id_objet
    ORDER BY e.date_emprunt DESC
";
$res_emprunts = mysqli_query($bdd, $sql_emprunts);
if (!$res_emprunts) {
    die("Erreur SQL (emprunts) : " . mysqli_error($bdd));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Details de l'objet <?= ($objet['nom_objet']) ?></title>
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
        <li class="nav-item"><a class="nav-link" href="chercher_membre.php">Chercher un membre</a></li>
        <li class="nav-item"><a class="nav-link" href="../index.php">Deconnexion</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container">
    <h1><?= ($objet['nom_objet']) ?></h1>
    <p><strong>Categorie :</strong> <?= ($objet['nom_categorie']) ?></p>
    <p><strong>Proprietaire :</strong> <?= ($objet['nom_membre']) ?></p>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'img_supprimee'): ?>
        <div class="alert alert-success">Image supprimee avec succès.</div>
    <?php endif; ?>

    <h3>Images</h3>
    <div class="d-flex flex-wrap gap-3 mb-4">
        <?php if ($res_images && mysqli_num_rows($res_images) > 0): ?>
            <?php while ($img = mysqli_fetch_assoc($res_images)): ?>
                <div class="border p-2" style="max-width: 180px;">
                    <img src="../uploads/<?= ($img['nom_image']) ?>" alt="Image" class="img-fluid mb-2">
                    <form method="POST" action="supprimer_image.php" onsubmit="return confirm('Supprimer cette image ?');">
                        <input type="hidden" name="id_image" value="<?= ($img['id_image']) ?>">
                        <button type="submit" class="btn btn-danger btn-sm w-100">Supprimer</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucune image disponible.</p>
            <img src="../uploads/default.png" alt="Image par defaut" style="max-width:200px;">
        <?php endif; ?>
    </div>

    <h3>Historique des emprunts</h3>
    <?php if ($res_emprunts && mysqli_num_rows($res_emprunts) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Emprunteur</th>
                    <th>Date debut</th>
                    <th>Date retour</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($emprunt = mysqli_fetch_assoc($res_emprunts)): ?>
                    <tr>
                        <td><?= ($emprunt['emprunteur']) ?></td>
                        <td><?= ($emprunt['date_emprunt']) ?></td>
                        <td><?= ($emprunt['date_retour'] ?? 'Non retourne') ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun emprunt enregistre.</p>
    <?php endif; ?>
    <a href="ajouter_image_objet.php?id=<?= $id_objet ?>" class="btn btn-secondary mb-3">Ajouter des images</a>

    <a href="liste_objets.php" class="btn btn-link mt-3">← Retour à la liste</a>
</div>
</body>
</html>
