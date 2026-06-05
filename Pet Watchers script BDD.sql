CREATE TABLE utilisateur(
   idUtilisateur INT AUTO_INCREMENT,
   nom VARCHAR(100)  NOT NULL,
   prenom VARCHAR(100)  NOT NULL,
   numTelephone CHAR(8)  NOT NULL,
   adresse VARCHAR(255)  NOT NULL,
   codePostal CHAR(5) NOT NULL,
   ville VARCHAR(255) NOT NULL,
   mail VARCHAR(100)  NOT NULL,
   mdphache VARCHAR(8000)  NOT NULL,
   PRIMARY KEY(idUtilisateur)
);

CREATE TABLE petSitter(
   idUtilisateur INT,
   nbMaxAnimaux TINYINT NOT NULL,
   PRIMARY KEY(idUtilisateur),
   FOREIGN KEY(idUtilisateur) REFERENCES utilisateur(idUtilisateur)
);

CREATE TABLE proprietaire(
   idUtilisateur INT,
   PRIMARY KEY(idUtilisateur),
   FOREIGN KEY(idUtilisateur) REFERENCES utilisateur(idUtilisateur)
);

CREATE TABLE demande(
   idDemande INT AUTO_INCREMENT,
   statut VARCHAR(20) NOT NULL DEFAULT 'En attente',
   dateDemande DATE NOT NULL,
   idProprietaire INT NOT NULL,
   idPetSitter INT NOT NULL,
   PRIMARY KEY(idDemande),
   FOREIGN KEY(idProprietaire) REFERENCES proprietaire(idUtilisateur),
   FOREIGN KEY(idPetSitter) REFERENCES petSitter(idUtilisateur)
);

CREATE TABLE jour(
   idJour INT AUTO_INCREMENT,
   libelle VARCHAR(10),
   PRIMARY KEY(idJour)
);

CREATE TABLE espece(
   idEspece TINYINT AUTO_INCREMENT,
   libelle VARCHAR(50)  NOT NULL,
   PRIMARY KEY(idEspece)
);

CREATE TABLE animal(
   numTatouage VARCHAR(100),
   nom VARCHAR(50)  NOT NULL,
   sexe BOOLEAN NOT NULL,
   idUtilisateur INT NOT NULL,
   idEspece TINYINT NOT NULL,
   PRIMARY KEY(numTatouage),
   FOREIGN KEY(idUtilisateur) REFERENCES proprietaire(idUtilisateur),
   FOREIGN KEY(idEspece) REFERENCES espece(idEspece)
);

CREATE TABLE contrat(
   idContrat INT AUTO_INCREMENT,
   dateDebut DATE NOT NULL,
   dateFin DATE NOT NULL,
   idUtilisateur INT NOT NULL,
   numTatouage VARCHAR(100) NOT NULL,
   PRIMARY KEY(idContrat),
   FOREIGN KEY(idUtilisateur) REFERENCES petSitter(idUtilisateur),
   FOREIGN KEY(numTatouage) REFERENCES animal(numTatouage)
);

CREATE TABLE etreDisponible(
   idUtilisateur INT,
   idJour INT ,
   tarif DECIMAL(15,2)   NOT NULL,
   PRIMARY KEY(idUtilisateur, idJour),
   FOREIGN KEY(idUtilisateur) REFERENCES petSitter(idUtilisateur),
   FOREIGN KEY(idJour) REFERENCES jour(idJour)
);

-- #################################################################

INSERT INTO espece (libelle) VALUES
	('Chien'),
	('Chat');
	
INSERT INTO jour (libelle) VALUES
	('Lundi'),
	('Mardi'),
	('Mercredi'),
	('Jeudi'),
	('Vendredi'),
	('Samedi'),
	('Dimanche');

-- #################################################################

DELIMITER $$
   CREATE OR REPLACE TRIGGER checkDoubleMail BEFORE INSERT ON utilisateur FOR EACH ROW
   BEGIN
      DECLARE currentMail VARCHAR(100);
      SELECT mail INTO currentMail
      FROM utilisateur
      WHERE mail = NEW.mail limit 1;

      IF(currentMail IS NOT NULL) THEN
         SIGNAL SQLSTATE '45001'
         SET MESSAGE_TEXT = 'Adresse mail déjà existante';
      END IF;
   END;
$$
DELIMITER ;

-- ##################################################################

-- ===== UTILISATEURS : PET SITTERS (id 1 à 10) =====
INSERT INTO utilisateur (nom, prenom, numTelephone, adresse, codePostal, ville, mail, mdphache) VALUES
('Martin',   'Lucas',    '0612345601', '12 rue des Lilas',        '75011', 'Paris',      'lucas.martin@mail.fr',    'a1b2c3d4'),
('Bernard',  'Emma',     '0612345602', '8 avenue Victor Hugo',    '69003', 'Lyon',       'emma.bernard@mail.fr',    'b2c3d4e5'),
('Dubois',   'Hugo',     '0612345603', '34 boulevard Gambetta',   '33000', 'Bordeaux',   'hugo.dubois@mail.fr',     'c3d4e5f6'),
('Thomas',   'Lea',      '0612345604', '5 impasse des Roses',     '31000', 'Toulouse',   'lea.thomas@mail.fr',      'd4e5f6a7'),
('Robert',   'Jules',    '0612345605', '21 rue Nationale',        '59000', 'Lille',      'jules.robert@mail.fr',    'e5f6a7b8'),
('Richard',  'Chloe',    '0612345606', '17 place de la Mairie',   '44000', 'Nantes',     'chloe.richard@mail.fr',   'f6a7b8c9'),
('Petit',    'Louis',    '0612345607', '3 chemin du Lac',         '67000', 'Strasbourg', 'louis.petit@mail.fr',     'a7b8c9d0'),
('Durand',   'Manon',    '0612345608', '45 rue Pasteur',          '34000', 'Montpellier','manon.durand@mail.fr',    'b8c9d0e1'),
('Leroy',    'Nathan',   '0612345609', '9 avenue de la Gare',     '06000', 'Nice',       'nathan.leroy@mail.fr',    'c9d0e1f2'),
('Moreau',   'Sarah',    '0612345610', '28 rue du Moulin',        '13001', 'Marseille',  'sarah.moreau@mail.fr',    'd0e1f2a3');

-- ===== UTILISATEURS : PROPRIETAIRES (id 11 à 20) =====
INSERT INTO utilisateur (nom, prenom, numTelephone, adresse, codePostal, ville, mail, mdphache) VALUES
('Simon',    'Theo',     '0698765411', '14 rue de la Paix',       '75015', 'Paris',      'theo.simon@mail.fr',      'e1f2a3b4'),
('Laurent',  'Camille',  '0698765412', '7 rue des Acacias',       '69006', 'Lyon',       'camille.laurent@mail.fr', 'f2a3b4c5'),
('Lefebvre', 'Maxime',   '0698765413', '52 cours de l''Intendance','33000','Bordeaux',  'maxime.lefebvre@mail.fr', 'a3b4c5d6'),
('Michel',   'Ines',     '0698765414', '11 allee des Chenes',     '31200', 'Toulouse',   'ines.michel@mail.fr',     'b4c5d6e7'),
('Garcia',   'Tom',      '0698765415', '6 rue Victor Hugo',       '59800', 'Lille',      'tom.garcia@mail.fr',      'c5d6e7f8'),
('David',    'Julie',    '0698765416', '38 quai de la Fosse',     '44100', 'Nantes',     'julie.david@mail.fr',     'd6e7f8a9'),
('Bertrand', 'Adam',     '0698765417', '2 rue Kleber',            '67100', 'Strasbourg', 'adam.bertrand@mail.fr',   'e7f8a9b0'),
('Roux',     'Lina',     '0698765418', '19 avenue du Pont',       '34070', 'Montpellier','lina.roux@mail.fr',       'f8a9b0c1'),
('Vincent',  'Noah',     '0698765419', '23 promenade des Anglais','06000', 'Nice',       'noah.vincent@mail.fr',    'a9b0c1d2'),
('Fournier', 'Eva',      '0698765420', '40 rue de la Republique', '13002', 'Marseille',  'eva.fournier@mail.fr',    'b0c1d2e3');

-- ===== SPECIALISATION PET SITTERS (id 1 à 10) =====
INSERT INTO petSitter (idUtilisateur, nbMaxAnimaux) VALUES
(1, 3), (2, 5), (3, 2), (4, 4), (5, 3),
(6, 6), (7, 2), (8, 5), (9, 3), (10, 4);

-- ===== SPECIALISATION PROPRIETAIRES (id 11 à 20) =====
INSERT INTO proprietaire (idUtilisateur) VALUES
(11), (12), (13), (14), (15), (16), (17), (18), (19), (20);

-- ===== ANIMAUX (espece : 1 = Chien, 2 = Chat ; sexe : 1 = male, 0 = femelle) =====
INSERT INTO animal (numTatouage, nom, sexe, idUtilisateur, idEspece) VALUES
-- Proprietaire 11 (3 animaux)
('FR250001', 'Rex',     1, 11, 1),
('FR250002', 'Mia',     0, 11, 2),
('FR250003', 'Filou',   1, 11, 1),
-- Proprietaire 12 (1 animal)
('FR250004', 'Nala',    0, 12, 2),
-- Proprietaire 13 (5 animaux)
('FR250005', 'Max',     1, 13, 1),
('FR250006', 'Luna',    0, 13, 2),
('FR250007', 'Oscar',   1, 13, 1),
('FR250008', 'Salem',   1, 13, 2),
('FR250009', 'Cookie',  0, 13, 2),
-- Proprietaire 14 (2 animaux)
('FR250010', 'Pacha',   1, 14, 2),
('FR250011', 'Bella',   0, 14, 1),
-- Proprietaire 15 (4 animaux)
('FR250012', 'Simba',   1, 15, 2),
('FR250013', 'Daisy',   0, 15, 1),
('FR250014', 'Tigrou',  1, 15, 2),
('FR250015', 'Snow',    0, 15, 2),
-- Proprietaire 16 (1 animal)
('FR250016', 'Rocky',   1, 16, 1),
-- Proprietaire 17 (3 animaux)
('FR250017', 'Minette', 0, 17, 2),
('FR250018', 'Gaston',  1, 17, 1),
('FR250019', 'Felix',   1, 17, 2),
-- Proprietaire 18 (2 animaux)
('FR250020', 'Lola',    0, 18, 1),
('FR250021', 'Pepper',  0, 18, 2),
-- Proprietaire 19 (5 animaux)
('FR250022', 'Buddy',   1, 19, 1),
('FR250023', 'Chipie',  0, 19, 2),
('FR250024', 'Diesel',  1, 19, 1),
('FR250025', 'Caramel', 0, 19, 2),
('FR250026', 'Eros',    1, 19, 1),
-- Proprietaire 20 (4 animaux)
('FR250027', 'Iris',    0, 20, 2),
('FR250028', 'Balto',   1, 20, 1),
('FR250029', 'Plume',   0, 20, 2),
('FR250030', 'Hercule', 1, 20, 1);

-- ===== DISPONIBILITES (jour : 1=Lundi ... 7=Dimanche) =====
INSERT INTO etreDisponible (idUtilisateur, idJour, tarif) VALUES
-- Pet sitter 1 : semaine
(1, 1, 15.00), (1, 2, 15.00), (1, 3, 15.00), (1, 4, 15.00), (1, 5, 15.00),
-- Pet sitter 2 : tous les jours
(2, 1, 20.00), (2, 2, 20.00), (2, 3, 20.00), (2, 4, 20.00), (2, 5, 20.00), (2, 6, 25.00), (2, 7, 25.00),
-- Pet sitter 3 : week-end
(3, 6, 18.50), (3, 7, 18.50),
-- Pet sitter 4 : lun/mer/ven
(4, 1, 16.00), (4, 3, 16.00), (4, 5, 16.00),
-- Pet sitter 5 : mar/jeu + samedi
(5, 2, 14.00), (5, 4, 14.00), (5, 6, 17.00),
-- Pet sitter 6 : tous les jours
(6, 1, 22.00), (6, 2, 22.00), (6, 3, 22.00), (6, 4, 22.00), (6, 5, 22.00), (6, 6, 28.00), (6, 7, 28.00),
-- Pet sitter 7 : week-end uniquement
(7, 6, 19.00), (7, 7, 21.00),
-- Pet sitter 8 : semaine
(8, 1, 17.50), (8, 2, 17.50), (8, 3, 17.50), (8, 4, 17.50), (8, 5, 17.50),
-- Pet sitter 9 : jeu/ven/sam/dim
(9, 4, 16.50), (9, 5, 16.50), (9, 6, 20.00), (9, 7, 20.00),
-- Pet sitter 10 : lundi à samedi
(10, 1, 18.00), (10, 2, 18.00), (10, 3, 18.00), (10, 4, 18.00), (10, 5, 18.00), (10, 6, 23.00);