<?php
function connexionBDD() {
    $host = "localhost";
    $user = "ETU004223";
    $pass = "8kEa3yOe";
    $db = "db_s2_ETU004223";
    $conn = mysqli_connect($host, $user, $pass, $db);
    if (!$conn) {
        die("Connexion echouee : " . mysqli_connect_error());
    }
    return $conn;
}

function getObjets($filtre = "") {
    $bdd = connexionBDD();
    $sql = "SELECT o.*, c.nom_categorie, e.date_retour
            FROM S2_objet o
            JOIN S2_categorie_objet c ON o.id_categorie = c.id_categorie
            LEFT JOIN S2_emprunt e ON o.id_objet = e.id_objet AND e.date_retour >= CURDATE()";
    if ($filtre != "") {
        $sql .= " WHERE o.id_categorie = $filtre";
    }
    return mysqli_query($bdd, $sql);
}

function getCategories() {
    $bdd = connexionBDD();
    $sql = "SELECT * FROM S2_categorie_objet";
    return mysqli_query($bdd, $sql);
}

function insererMembre($nom, $date_naissance, $genre, $email, $ville, $mdp, $image_profil) {
    $bdd = connexionBDD();

    $sql = "INSERT INTO S2_membre (nom, date_naissance, genre, email, ville, mdp, image_profil)
            VALUES ('$nom', '$date_naissance', '$genre', '$email', '$ville', '$mdp', '$image_profil')";

    return mysqli_query($bdd, $sql);
}
function verifierConnexion($email, $mdp) {
    $bdd = connexionBDD();
    $sql = "SELECT * FROM S2_membre WHERE email = '$email' AND mdp = '$mdp'";
    $res = mysqli_query($bdd, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        return mysqli_fetch_assoc($res); 
    } else {
        return false;
    }
}
function ajouterImageObjet($id_objet, $fichier_image) {
    $bdd = connexionBDD();


    $dossier_upload = __DIR__ . '/../uploads/';

    
    $nom_fichier = basename($fichier_image['name']);

    
    $chemin_destination = $dossier_upload . $nom_fichier;

    
    if (move_uploaded_file($fichier_image['tmp_name'], $chemin_destination)) {
        
        $sql = "INSERT INTO S2_images_objet (id_objet, nom_image) VALUES ('$id_objet', '$nom_fichier')";
        if (mysqli_query($bdd, $sql)) {
            return true;
        } else {
            
            return "Erreur SQL : " . mysqli_error($bdd);
        }
    } else {
        return "Erreur lors du déplacement du fichier.";
    }
}
function getImagesObjet($id_objet) {
    $bdd = connexionBDD();
    $sql = "SELECT nom_image FROM S2_images_objet WHERE id_objet = $id_objet";
    return mysqli_query($bdd, $sql);
}
?>

