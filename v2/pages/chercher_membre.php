<?php
session_start();
include("../inc/fonction.php");
if (!isset($_SESSION["id_membre"])) {
    header("Location: ../index.php");
    exit();
}

$nom_recherche = $_GET['nom'] ?? "";
$membres = chercherMembres($nom_recherche);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Recherche de membres</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
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
          <a class="nav-link" href="../index.php">Déconnexion</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main class="container mt-4">
    <h2>Rechercher un membre</h2>

    <form method="GET" class="mb-3" action="chercher_membre.php">
        <input type="text" name="nom" class="form-control" placeholder="Nom du membre" value="<?= htmlspecialchars($nom_recherche) ?>" />
        <button type="submit" class="btn btn-primary mt-2">Rechercher</button>
    </form>

    <?php if ($membres && mysqli_num_rows($membres) > 0): ?>
        <ul class="list-group">
            <?php while ($m = mysqli_fetch_assoc($membres)): ?>
                <li class="list-group-item">
                    <a href="voir_membre.php?id=<?= $m['id_membre'] ?>">
                        <?= ($m['nom']) ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Aucun membre trouvé.</p>
    <?php endif; ?>
</main>
</body>
</html>
