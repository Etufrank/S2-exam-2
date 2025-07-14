<?php
session_start();
include("../inc/fonction.php");

if (!isset($_SESSION["id_membre"])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_image'])) {
    $id_image = intval($_POST['id_image']);
    $bdd = connexionBDD();

    $sql = "SELECT nom_image, id_objet FROM S2_images_objet WHERE id_image = $id_image";
    $res = mysqli_query($bdd, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $img = mysqli_fetch_assoc($res);
        $chemin = __DIR__ . '/../uploads/' . $img['nom_image'];

        if (file_exists($chemin)) {
            unlink($chemin);
        }

        $del = "DELETE FROM S2_images_objet WHERE id_image = $id_image";
        mysqli_query($bdd, $del);

        header("Location: details_objet.php?id=" . $img['id_objet'] . "&msg=img_supprimee");
        exit();
    } else {
        echo "Image non trouvée.";
    }
} else {
    echo "Requête invalide.";
}
?>
