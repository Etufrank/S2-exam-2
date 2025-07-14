<?php
include("../inc/fonction.php");
ini_set('display_errors', 1); error_reporting(E_ALL);

$bdd = connexionBDD();

$categorie = isset($_GET['categorie']) ? $_GET['categorie'] : "";
$message = "";


$cat_res = mysqli_query($bdd, "SELECT * FROM S2_categorie_objet");


$obj_res = ($categorie != "") 
    ? mysqli_query($bdd, "SELECT id_objet, nom_objet FROM S2_objet WHERE id_categorie = '$categorie'")
    : false;


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_objet"])) {
    $id_objet = $_POST["id_objet"];
    $image_name = $_FILES["image"]["name"];
    $tmp = $_FILES["image"]["tmp_name"];
    $dest = "../uploads/" . $image_name;

    if (move_uploaded_file($tmp, $dest)) {
        $sql = "INSERT INTO S2_images_objet (id_objet, nom_image) VALUES ('$id_objet', '$image_name')";
        if (mysqli_query($bdd, $sql)) {
            $message = "<p style='color:green;'>Image ajoutée avec succès.</p>";
        } else {
            $message = "<p style='color:red;'>Erreur SQL : " . mysqli_error($bdd) . "</p>";
        }
    } else {
        $message = "<p style='color:red;'>Échec de l'envoi du fichier.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ajouter une image à un objet</title>
    <link rel="stylesheet" href="../assets/css/upload.css">
</head>
<body>
    <h2>Ajouter une image à un objet</h2>
    <?= $message ?>

    <form method="GET">
        <label>Choisir une catégorie :</label>
        <select name="categorie" onchange="this.form.submit()">
            <option value="">-- Sélectionner --</option>
            <?php while ($cat = mysqli_fetch_assoc($cat_res)): ?>
                <option value="<?= $cat['id_categorie'] ?>" <?= ($categorie == $cat['id_categorie']) ? "selected" : "" ?>>
                    <?= $cat['nom_categorie'] ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    
    <?php if ($categorie && $obj_res && mysqli_num_rows($obj_res) > 0): ?>
        <form method="POST" enctype="multipart/form-data">
            <br>
            <label>Choisir un objet :</label><br>
            <select name="id_objet" required>
                <?php while ($obj = mysqli_fetch_assoc($obj_res)): ?>
                    <option value="<?= $obj['id_objet'] ?>"><?= $obj['nom_objet'] ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Choisir une image :</label><br>
            <input type="file" name="image" accept="image/*" required><br><br>

            <button type="submit">Ajouter l'image</button>
        </form>
    <?php elseif ($categorie): ?>
        <p>Aucun objet trouvé pour cette catégorie.</p>
    <?php endif; ?>

    <a href="liste_objets.php">Liste</a>
</body>
</html>
