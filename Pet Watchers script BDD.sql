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