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
function ajouterObjet($nom, $id_categorie, $id_membre) {
    $bdd = connexionBDD();
    $sql = "INSERT INTO S2_objet (nom_objet, id_categorie, id_membre) VALUES ('$nom', '$id_categorie', '$id_membre')";
    if (mysqli_query($bdd, $sql)) {
        
        return true;
    }
    return false;
}

function getLastObjetId($nom, $id_categorie, $id_membre) {
    $bdd = connexionBDD();
    $sql = "SELECT id_objet FROM S2_objet WHERE nom_objet='$nom' AND id_categorie='$id_categorie' AND id_membre='$id_membre' ORDER BY id_objet DESC LIMIT 1";
    $res = mysqli_query($bdd, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        return $row['id_objet'];
    }
    return false;
}

function getObjets2($categorie = "", $nom = "", $dispo = false) {
    $bdd = connexionBDD();

    $sql = "SELECT o.*, c.nom_categorie, e.date_retour
            FROM S2_objet o
            JOIN S2_categorie_objet c ON o.id_categorie = c.id_categorie
            LEFT JOIN S2_emprunt e ON o.id_objet = e.id_objet AND e.date_retour >= CURDATE()";

    $conditions = [];

    if ($categorie != "") {
        $conditions[] = "o.id_categorie = " . intval($categorie);
    }

    if ($nom != "") {
        $conditions[] = "o.nom_objet LIKE '%" . mysqli_real_escape_string($bdd, $nom) . "%'";
    }

    if ($dispo) {
        $conditions[] = "e.date_retour IS NULL";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    return mysqli_query($bdd, $sql);
}
function getInfosMembre($id_membre) {
    $bdd = connexionBDD();
    $sql = "SELECT * FROM S2_membre WHERE id_membre = $id_membre";
    return mysqli_fetch_assoc(mysqli_query($bdd, $sql));
}

function getObjetsParCategorieDuMembre($id_membre) {
    $bdd = connexionBDD();
    $sql = "SELECT c.nom_categorie, o.nom_objet
            FROM S2_objet o
            JOIN S2_categorie_objet c ON o.id_categorie = c.id_categorie
            WHERE o.id_membre = $id_membre
            ORDER BY c.nom_categorie";
    return mysqli_query($bdd, $sql);
}
function chercherMembres($nom = "") {
    $bdd = connexionBDD();
    $nom = mysqli_real_escape_string($bdd, $nom);
    $sql = "SELECT * FROM S2_membre WHERE nom LIKE '%$nom%'";
    return mysqli_query($bdd, $sql);
}

?>

