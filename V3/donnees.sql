CREATE TABLE S2_membre (
    id_membre INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    date_naissance DATE,
    genre ENUM('H','F'),
    email VARCHAR(100),
    ville VARCHAR(50),
    mdp VARCHAR(255),
    image_profil VARCHAR(100)
);

CREATE TABLE S2_categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(50)
);

CREATE TABLE S2_objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100),
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES S2_categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES S2_membre(id_membre)
);

CREATE TABLE S2_images_objet (
    id_image INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(100),
    FOREIGN KEY (id_objet) REFERENCES S2_objet(id_objet)
);

CREATE TABLE S2_emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_objet) REFERENCES S2_objet(id_objet),
    FOREIGN KEY (id_membre) REFERENCES S2_membre(id_membre)
);
USE dbname;

-- Membres (4)
INSERT INTO S2_membre (nom, date_naissance, genre, email, ville, mdp, image_profil) VALUES
('Alice', '1990-05-12', 'F', 'alice@mail.com', 'Antananarivo', 'mdpAlice', 'alice.jpg'),
('Bob', '1985-10-01', 'H', 'bob@mail.com', 'Toamasina', 'mdpBob', 'bob.jpg'),
('Claire', '1992-08-22', 'F', 'claire@mail.com', 'Fianarantsoa', 'mdpClaire', 'claire.jpg'),
('David', '1988-03-15', 'H', 'david@mail.com', 'Antsirabe', 'mdpDavid', 'david.jpg');

-- Categories (4)
INSERT INTO S2_categorie_objet (nom_categorie) VALUES
('esthetique'),
('bricolage'),
('mecanique'),
('cuisine');

-- Objets (10 par membre = 40 objets)
-- On répartit les objets en boucle sur catégories (1 à 4), pour simplifier

INSERT INTO S2_objet (nom_objet, id_categorie, id_membre) VALUES
-- Alice (id_membre=1)
('Vernis à ongles', 1, 1),
('Perceuse', 2, 1),
('Clé à molette', 3, 1),
('Mixeur', 4, 1),
('Fond de teint', 1, 1),
('Marteau', 2, 1),
('Pneu de vélo', 3, 1),
('Casserole', 4, 1),
('Mascara', 1, 1),
('Tournevis', 2, 1),

-- Bob (id_membre=2)
('Rouge à lèvres', 1, 2),
('Scie sauteuse', 2, 2),
('Batterie auto', 3, 2),
('Poêle', 4, 2),
('Crème visage', 1, 2),
('Clé anglaise', 3, 2),
('Perceuse-visseuse', 2, 2),
('Fouet', 4, 2),
('Sèche-cheveux', 1, 2),
('Pince multiprise', 3, 2),

-- Claire (id_membre=3)
('Sérum', 1, 3),
('Tournevis cruciforme', 2, 3),
('Filtre à huile', 3, 3),
('Robot de cuisine', 4, 3),
('Poudre libre', 1, 3),
('Niveau à bulle', 2, 3),
('Embrayage', 3, 3),
('Planche à découper', 4, 3),
('Lisseur', 1, 3),
('Couteau de cuisine', 4, 3),

-- David (id_membre=4)
('Crème solaire', 1, 4),
('Cloueuse', 2, 4),
('Amortisseur', 3, 4),
('Mixeur plongeant', 4, 4),
('Baume à lèvres', 1, 4),
('Perceuse à colonne', 2, 4),
('Filtre à air', 3, 4),
('Balance de cuisine', 4, 4),
('Mousse coiffante', 1, 4),
('Tournevis plat', 2, 4);

-- Emprunts (10 au total, quelques objets empruntés par certains membres)
INSERT INTO S2_emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(2, 2, '2025-07-01', '2025-07-10'),
(5, 3, '2025-06-20', '2025-07-05'),
(8, 1, '2025-07-05', '2025-07-15'),
(11, 4, '2025-07-02', '2025-07-12'),
(14, 1, '2025-06-25', '2025-07-04'),
(19, 2, '2025-07-07', '2025-07-17'),
(22, 3, '2025-07-03', '2025-07-13'),
(25, 4, '2025-07-01', '2025-07-11'),
(30, 1, '2025-07-06', '2025-07-16'),
(33, 2, '2025-07-04', '2025-07-14');
