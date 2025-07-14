<?php
session_start();
include("../inc/fonction.php");

if (!isset($_SESSION["id_membre"])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bdd = connexionBDD();

    $nom = $_POST["nom_objet"];
    $id_categorie = $_POST["id_categorie"];
    $id_membre = $_SESSION["id_membre"];


    $id_objet = ajouterObjet($nom, $id_categorie, $id_membre);

    if ($id_objet !== false) {
        
        if (!empty($_FILES["images"]["name"][0])) {
            foreach ($_FILES["images"]["tmp_name"] as $i => $tmp_name) {
                $fichier = [
                    'name' => $_FILES["images"]["name"][$i],
                    'tmp_name' => $tmp_name
                ];
                $res = ajouterImageObjet($id_objet, $fichier);
                if ($res !== true) {
                    $_SESSION['message'] = "Erreur lors de l'ajout de l'image : $res";
                    header("Location: ajout.php");
                    exit();
                }
            }
        }
        $_SESSION['message'] = "Objet ajouté avec succès !";
    } else {
        $_SESSION['message'] = "Erreur lors de l'ajout de l'objet.";
    }
} else {
    $_SESSION['message'] = "Méthode non autorisée.";
}

header("Location: ajout.php");
exit();
