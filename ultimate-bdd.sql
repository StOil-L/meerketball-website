# CREATION BBD
CREATE DATABASE IF NOT EXISTS suricates;

USE suricates;

#DROPAGE
DROP TABLE IF EXISTS Championnat, Arbre, Equipe, Joueur, ListeEquipe, ListeJoueur, Rencontre, Tournoi, Utilisateur;

#CREATAGE
CREATE TABLE Championnat (
IdChampionnat INT AUTO_INCREMENT,
Equipe1 INT REFERENCES Equipe(IdEquipe),
Equipe2 INT REFERENCES Equipe(IdEquipe),
Rencontre INT REFERENCES Rencontre(IdRencontre),
Vainqueur INT,
Tournoi INT REFERENCES Tournoi(Idtournoi),    
PRIMARY KEY(IdChampionnat)    
);    


CREATE TABLE Joueur (
IdJoueur INT AUTO_INCREMENT,
NumJoueur INT,
Nom VARCHAR(75),
Prenom VARCHAR(75),
DateNaissance DATE,
Categorie ENUM('Poussin', 'Junior', 'Senior', 'Benjamin','FeminineSenior','Handicap'),
AnneeInscription DATE,
Images VARCHAR(255),

PRIMARY KEY (IdJoueur)
);

CREATE TABLE Utilisateur (
IdUtilisateur INT AUTO_INCREMENT,
LoginU VARCHAR(255),
MotDePasse VARCHAR(255),
EMail varchar(255),
DatesInscription DATE,
Nom VARCHAR(75),
Prenom VARCHAR(75), 
RoleU ENUM('Administrateur', 'Gestionnaire', 'Utilisateur'),
Images VARCHAR(255),
OptionDalt BIT(1),
Joueur INT REFERENCES Joueur(IdJoueur),

PRIMARY KEY (IdUtilisateur)
);

CREATE TABLE Equipe (
IdEquipe INT AUTO_INCREMENT,
Categorie ENUM('Poussin', 'Junior', 'Senior', 'Benjamin','FeminineSenior','Handicap'),
Nom VARCHAR(255),
Niveau INT,
Mail VARCHAR(255),
Telephone VARCHAR(10),
Images VARCHAR(255),

PRIMARY KEY (IdEquipe)
);

CREATE TABLE ListeJoueur (
IdListeJoueur INT AUTO_INCREMENT,
IdEquipe INT REFERENCES Equipe(IdEquipe),
IdJoueur INT REFERENCES Joueur(IdJoueur),
Capitaine BIT(1),

PRIMARY KEY (IdListeJoueur)
);

CREATE TABLE Tournoi (
IdTournoi INT AUTO_INCREMENT,
IdGestionnaire INT NOT NULL REFERENCES Utilisateur(IdUtilisateur),
Nom VARCHAR(255),
EtatTournoi ENUM('Phase inscription', 'En cours', 'Acheve'),
DatesDebut DATE,
Duree INT,
Lieu VARCHAR(1000),
NbEquipeMax INT,
TypeT ENUM('Coupe', 'Championnat', 'Poule', 'PlusieursTours'),
Images VARCHAR(255),
Categorie ENUM('Poussin', 'Junior', 'Senior', 'Benjamin','FeminineSenior','Handicap'),

PRIMARY KEY (IdTournoi)
);

CREATE TABLE ListeEquipe (
IdListeEquipe INT AUTO_INCREMENT,
IdEquipe INT REFERENCES Equipe(IdEquipe),
IdTournoi INT REFERENCES Tournoi(IdTournoi),
AttenteInscription BIT(1) NOT NULL DEFAULT 0,
DateInscription datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,

PRIMARY KEY (IdListeEquipe)
);

CREATE TABLE Rencontre (
IdRencontre INT AUTO_INCREMENT,
Equipe1 INT REFERENCES Equipe(IdEquipe),
Equipe2 INT REFERENCES Equipe(IdEquipe),
DateRencontre DATETIME,
Lieu VARCHAR(255),
Duree INT,
TempsAdditionnel INT,
EtatRencontre ENUM('En attente', 'Termine', 'Annule', 'A venir'),
Vainqueur INT REFERENCES Equipe(IdEquipe),
Tournoi INT REFERENCES Tournoi(IdTournoi),
ScoreEquipe1 INT DEFAULT NULL,
ScoreEquipe2 INT DEFAULT NULL,
Images VARCHAR(255),

PRIMARY KEY (IdRencontre)
);

CREATE TABLE Arbre (
IdArbre INT AUTO_INCREMENT,
Tournoi INT NOT NULL REFERENCES Tournoi(IdTournoi),
Equipe1 INT REFERENCES Equipe(IdEquipe),
Equipe2 INT REFERENCES Equipe(IdEquipe),
Rencontre INT REFERENCES Rencontre(IdRencontre),

Hauteur INT,
PereGauche INT REFERENCES Arbre(IdArbre),
PereDroit INT REFERENCES Arbre(IdArbre),
Fils INT REFERENCES Arbre(IdArbre),

PRIMARY KEY (IdArbre)
);

#INSERTAGE
#Joueur
INSERT INTO Joueur (NumJoueur, Nom, Prenom, DateNaissance, Categorie, AnneeInscription, Images) VALUES
(3, 'Hawk', 'Mike', '2002-04-27 00:00:00', 'Junior', '2021-02-17 00:00:00', 'https://www.numerama.com/wp-content/uploads/2020/09/amons-ug-une.jpg'),
(12, 'Polnareff', 'Jean-Pierre', '1987-06-14 00:00:00', 'Senior', '2021-01-29 00:00:00', 'https://www.numerama.com/wp-content/uploads/2020/09/amons-ug-une.jpg'),
(16, 'Itches', 'Gabe', '2009-08-10 00:00:00', 'Junior', '2021-02-03 00:00:00', 'https://www.numerama.com/wp-content/uploads/2020/09/amons-ug-une.jpg'),
(2, 'Maraoui', 'Karim', '2001-09-11 00:00:00', 'Senior', '2021-01-06 00:00:00', 'https://www.numerama.com/wp-content/uploads/2020/09/amons-ug-une.jpg'),
(9, 'Soulier', 'Julien', '2013-05-09 00:00:00', 'Poussin', '2021-02-15 00:00:00', 'https://www.numerama.com/wp-content/uploads/2020/09/amons-ug-une.jpg'),
(11, 'Narukami', 'Yu', '1995-10-05 00:00:00', 'Junior', '2021-02-14 00:00:00', 'https://www.numerama.com/wp-content/uploads/2020/09/amons-ug-une.jpg'),
(7, 'Jackson', 'Michael', '1971-04-27 00:00:00', 'Senior', '2020-10-31 00:00:00', 'https://www.numerama.com/wp-content/uploads/2020/09/amons-ug-une.jpg'),
(21, 'Hawk', 'Mike', '2002-04-27 00:00:00', 'Junior', '2021-02-17 00:00:00', 'https://www.numerama.com/wp-content/uploads/2020/09/amons-ug-une.jpg'),
(24, 'Puccio', 'Jean-Marc', '1965-04-19 00:00:00', 'Senior', '2021-02-01 00:00:00', 'https://www.numerama.com/wp-content/uploads/2020/09/amons-ug-une.jpg'),
(69, 'Hawk', 'Mike', '2002-04-27 00:00:00', 'Junior', '2021-02-17 00:00:00', 'https://www.numerama.com/wp-content/uploads/2020/09/amons-ug-une.jpg');

#Utilisateur
INSERT INTO Utilisateur (LoginU, MotDePasse,EMail, DatesInscription, Nom, Prenom, RoleU, Images, OptionDalt, Joueur) VALUES 
('BellePomme' , '$2y$10\$l6SJoZK2i4ttWJyvxSnBAeaSLrkH.llecupyVVN4d69QA4Rh0aKNK', 'machin1@gmail.com', '2018-08-16', 'Rose', 'Aphrodite', 'Utilisateur', 'pomme.png', 0, NULL),
('BGdelOlympe', '$2y$10\$TxrjNaKzITdj6u9nxipo0.SydzF3cfr6ZJ/7YDuDGp3d.L.fWGoEy', 'machin2@gmail.com', '2017-07-16', 'Marguerite', 'Apollon', 'Utilisateur', 'BG.png', 0, 10),
('RebelleAntique', '$2y$10\$dzM1JnWOUreKKl2WqJoIjOA3sn/EqtepXoeRf.fofROtFb7GrBuLe', 'bidule1@hotmail.fr', '2022-08-16', 'Tournesol', 'Artémis', 'Gestionnaire', 'rebelle.png', 1, 3),
('LeMéchant', '$2y$10$8W1iklO1SouFVH7rTZqrXuMae3crpprp3DLIj2cWb16UFYzmBVnhO', 'bidule2@gmail.com', '2022-08-10', 'Pissenlit', 'Hadès', NULL, 'Gru.png', 0, NULL),
('Boss', '$2y$10\$EH5Gs9JsDy5Sm10dQQlUyeCqCZR1Ya7jaGxxUrGGOj9jPqkNXhp8G', 'pimpampoum@gmail.com', '2020-12-06', NULL, 'Zeus', 'Administrateur', NULL, 0, 8),
('PoissonBleu', '$2y$10\$q74qB5GFkPPxmBhPd90PsuZsEZia4W8A1f07gb7IR3V8AS5OoXKIm', 'gmlkgmflklfkmmmlkmmlkmlkkmlkmlrtktelrkmlrtkelktrlekgfkdjbl@gmail.com', '2017-05-03', 'Muguet', 'Poséidon', 'Gestionnaire', 'poisson.png', 0, NULL),
('LaCouillonne', '$2y$10\$S7m3cUkrkCbqcS9ywMUR4.EBpBz.yxHshzTuryTIiCOm3TGpCfIQG', 'couillonutemaximus@etu.univ-montp3.fr', '2024-04-16', 'Lys', 'Héra', 'Utilisateur', NULL, 0, NULL),
('Voyageur', '$2y$10$7x3sBFcXBpznbq9PPecUBuccNYF6/C5T2fR06Nr.HKmja5isQB236', '1re.zd.@etu.montpellier.fr', '2021-08-16', 'Tulipe', 'Atlas', 'Gestionnaire', NULL, 0, NULL),
('JaimeLeVin', '$2y$10\$Tt0sEkuNycIn01WPD61WhesVsTFXTQfDhHAFNxu2RYNnG1vV3lF5G', 'er.azfe.@outlook.fr', '2019-08-31', 'Coquelicot', 'Dionysos', 'Utilisateur', 'bouteille.png', 0, 9),
('MamanPoule', '$2y$10\$p.mijRdGsq2MXmXVrmyXgO1El9Azwuy.uw1oDL3VUUrCBkfiU/IDi', 'fulanito@gmail.com', '2027-11-29', 'Bleuet', 'Gaïa', 'Administrateur', 'poule.png', 0, NULL);

#Equipe
INSERT INTO Equipe (Categorie, Nom, Niveau, Mail, Telephone, Images) VALUES 
('Poussin', 'los pollos locos', 5,'lucasLePoussin@etu.umontpellier.fr', '0601020304', 'stringRose.png'),
('Poussin', 'nomDEQUIPE', 3, 'LePoussin@etu.umontpellier.fr', '0602020304', 'NULL.png'),
('Junior', 'kangoo junior', 2,'vroumVroumFaitLaKangoo@etu.umontpellier.fr', '0601020304', 'flappyLeGourou.png'),
('Junior', 'Holla jeffe, tou veux oune cérbéssa', 5,'alcoolique@etu.umontpellier.fr', '0603020304', 'alcooliqueAnonyme.png'),
('Senior', 'les huitres, cest bon!', 4,'mangeurDhuitre@etu.umontpellier.fr', NULL, 'huitre.png'),
('Senior', 'leau ça mouille', 8,'brrr@etu.umontpellier.fr', '0606020304', 'water.png'),
('Poussin', 'Tes fou Mon uniforme sort de chez le pressing', 18,NULL, '0604020304', 'virilité.png'),
('Senior', 'les guez', 1,'guez@etu.umontpellier.fr', '0610020304', 'merguez.png'),
('Junior', 'les chipos', 20,'chipos@etu.umontpellier.fr', '0620020304', 'chipolatas.png'),
('Poussin', 'Amour en gelé', 55,'Jetaime@etu.umontpellier.fr', '0631020304', 'antijuron.png');

#ListeJoueur
INSERT INTO ListeJoueur (IdEquipe, IdJoueur, Capitaine) VALUES 
(10, 10, 1),
(10, 1, 0),
(10, 2, 0),
(1, 3, 1),
(1, 4, 0),
(3, 8, 1),
(3, 6, 0),
(3, 7, 0),
(4, 10, 1),
(8, 9, 0);

#Tournoi
INSERT INTO Tournoi (IdGestionnaire, Nom, EtatTournoi, DatesDebut, Duree, Lieu, NbEquipeMax, TypeT, Images, Categorie) VALUES 
(3, '6 Nations','Phase inscription', '2022-08-16', 90, 'France-Grande-Bretagne', 6, 'Championnat', '6nation.png', 'Senior'),
(3, 'Tour du Lez', 'Phase inscription', '2022-03-01', 70, 'Montpellier', 12, 'Poule', 'tour_du_lez.png', 'FeminineSenior'),
(3, 'Basket et Prunes','Phase inscription', '2025-12-25', 6, 'Thionville', 4, 'Championnat', 'ba-et-pru.png', 'Poussin'),
(3, 'Elementaire mon cher basket','Phase inscription', '2023-04-01', 55, 'Londres-Paris', 8, 'PlusieursTours', 'elembasket.png', 'Senior'),
(3, 'BASQUE-ET','Phase inscription', '2024-09-17', 12, 'Bordeaux', 6, 'Coupe', 'paysbasquet.png', 'Junior'),
(3, 'Babasket au rhum','Phase inscription', '2027-06-03', 190, 'Bretagne', 12, 'Championnat', 'babasket.png', 'FeminineSenior'),
(6, 'Du basket et des hommes','En cours', '2021-01-29', 365, 'Salle polyvalente de St-Jean de Cuculles', 4, 'Coupe', 'sexism.png', 'Senior'),
(6, 'Basket-folie-2009', 'Phase inscription','2022-02-22', 100, 'France', 6, 'Poule', 'folier2009.png', 'Junior'),
(6, 'placeholder', 'Phase inscription','2023-02-16', 90, NULL, 8, 'Championnat', NULL, 'Benjamin'),
(6, '1-2-3-tournoi!','Phase inscription', '2025-02-20', 90, 'France-Grande-Bretagne', 10, 'PlusieursTours', '123tournoi-pointexclamation.png', 'Poussin'),
(8, 'Championnat des Super-Licornes', 'Acheve', '2018-03-17', 35, 'Pays des Licornes', 8, 'Championnat', 'licorne.png', 'Poussin'),
(8, 'La bataille des Grenouilles','Acheve', '2019-03-01', 1, 'Etang vert', 4, 'Poule', 'grenouille.png', 'Poussin'),
(8, 'Les portes contre-attaquent', 'En cours', '2021-02-01', 90, 'Couloir', 4, 'Championnat', 'serrure.png', 'Benjamin'),
(8, 'Combat de poules','En cours', '2020-09-03', 365, 'Poulailler', 8, 'Coupe', 'cocorico.png', 'FeminineSenior'),
(8, 'Ce-Tournoi-A-Le-Premier-Tour-Termine', 'En cours', '2021-02-21', 180, 'Chez moi', 8, 'Championnat', 'default.png', 'Handicap');

#ListeEquipe
INSERT INTO ListeEquipe (IdEquipe, IdTournoi, AttenteInscription, DateInscription) VALUES 
(10, 1, 1, '2021-06-16 00:00:00'),
(1, 1, 0, '2021-06-16 00:08:00'),
(2, 1, 1, '2021-09-20 00:00:00'),
(4, 2, 1, '2021-10-16 00:00:00'),
(5, 2, 0, '2021-12-16 00:00:00'),
(6, 2, 0, '2021-12-16 00:00:00'),
(7, 2, 0, '2021-12-16 00:00:00'),
(8, 2, 0, '2021-12-16 00:00:00'),
(9, 2, 0, '2021-12-16 00:00:00'),
(10, 2, 0, '2021-12-16 00:00:00'),
(1, 2, 0, '2021-12-16 00:00:00'),
(2, 2, 0, '2021-12-16 00:00:00'),
(3, 2, 0, '2021-12-16 00:00:00'),
(1, 6, 1, '2021-01-15 00:00:00'),
(2, 6, 0, '2021-02-16 00:00:00'),
(3, 6, 1, '2021-03-19 00:00:00'),
(9, 6, 0, '2021-05-16 00:00:00'),
(5, 4, 1, '2021-01-15 00:00:00'),
(3, 4, 0, '2021-02-16 00:00:00'),
(4, 4, 1, '2021-03-19 00:00:00'),
(8, 4, 0, '2021-05-16 00:00:00'),
(3, 10, 0, '2022-06-30 00:00:00'),
(4, 10, 0, '2021-10-16 00:00:00'),
(5, 12, 1, '2021-12-16 00:00:00'),
(1, 12, 1, '2021-01-15 00:00:00'),
(2, 12, 1, '2021-02-16 00:00:00'),
(3, 12, 1, '2021-03-19 00:00:00'),
(9, 11, 0, '2021-05-16 00:00:00'),
(5, 11, 1, '2021-01-15 00:00:00'),
(3, 11, 0, '2021-02-16 00:00:00'),
(4, 11, 0, '2021-03-19 00:00:00'),
(1, 15, 0, '2021-06-16 00:00:00'), 
(2, 15, 0, '2021-06-16 00:00:00'), 
(3, 15, 0, '2021-06-16 00:00:00'), 
(4, 15, 0, '2021-06-16 00:00:00'), 
(5, 15, 0, '2021-06-16 00:00:00'), 
(6, 15, 0, '2021-06-16 00:00:00'), 
(7, 15, 0, '2021-06-16 00:00:00'), 
(8, 15, 0, '2021-06-16 00:00:00'); 

#Rencontre
INSERT INTO Rencontre (Equipe1, Equipe2, DateRencontre, Lieu, Duree, TempsAdditionnel, EtatRencontre, Vainqueur, Tournoi, ScoreEquipe1, ScoreEquipe2, Images) VALUES 
(10,1, '2022-08-17 14:30:00','Montpellier',90,0,'En attente',NULL,0,0,0,'Montpellier.png'),
(2,10, '2023-04-03 16:00:00','Nîmes',90,183,'Termine',2,3,NULL,NULL,'Nîmes.png'),
(1,2, '2026-01-01 00:00:00','St-Clement Ferrier',90,15,'Termine',1,2,NULL,NULL,'St-Clement Ferrier.png'),
(3,1, '2024-09-17 18:30:00','Canet',90,0,'Annule',NULL,4,0,0,'Canet.png'),
(3,10, '2022-03-02 11:30:00','Canet',90,5,'Termine',10,1,10,45,'Canet.png'),
(2,3, '2023-02-20 10:00:00','Nîmes',90,0,'Termine',3,8,NULL,NULL,'Nîmes.png'),
(4,1, '2025-02-20 17:00:00','Lunel',90,350,'Termine',NULL,9,0,0,'Lunel.png'),
(10,4, '2024-09-19 16:45:00','Montpellier',90,57,'Termine',10,4,NULL,NULL,'Montpellier.png'),
(4,2, '2025-12-25 08:00:00','Lunel',90,25,'Termine',4,2,2,0,'Lunel.png'),
(4,3, '2022-08-16 09:11:00','Lunel',90,0,'En attente',NULL,0,0,0,'Lunel.png'),
(1, 3, '2018-03-20 14:00:00', 'Montpellier', 90, 0, 'Termine', 3, 11, NULL, NULL,'Montpellier.png'),
(4, 2, '2018-03-20 16:00:00', 'Montpellier', 90, 0, 'Termine', 2, 11, 2, 4,'Montpellier.png'),
(5, 7, '2018-03-27 14:00:00', 'Pérols', 90, 0, 'Termine', 5, 11, 4, 3,'Pérols.png'),
(8, 6, '2018-03-27 16:00:00', 'Pérols', 90, 0, 'Termine', 8, 11, NULL, NULL,'Pérols.png'),
(3, 2, '2018-04-12 12:00:00', NULL, NULL, 0, 'Annule', NULL, 11, NULL, NULL, NULL),
(3, 2, '2018-04-12 14:00:00', 'Nîmes', 90, 0, 'Termine', 3, 11, NULL, NULL, 'Nîmes.png'),
(5, 8, '2018-04-12 16:00:00', 'Nîmes', 90, 0, 'Termine', 5, 11, 2, 0, 'Nîmes.png'),
(3, 5, '2018-04-20 14:00:00', 'Bordeaux', 90, 0, 'Termine', 5, 11, 9, 11, 'Bordeaux.png'),
(1, 5, '2019-03-01 10:00:00', 'Lyon', 55, 0, 'Termine', 5, 12, 3, 5, 'Lyon.png'),
(6, 9, '2019-03-01 11:00:00', 'Lyon', 55, 0, 'Termine', 9, 12, 4, 5, 'Lyon.png'),
(5, 9, '2019-03-01 12:00:00', 'Lyon', 55, 0, 'Termine', 9, 12, 7, 9, 'Lyon.png'),
(1, 3, '2020-09-20 14:00:00', 'Montpellier', 90, 0, 'Termine', 3, 14, NULL, NULL,'Montpellier.png'),
(4, 2, '2020-09-20 16:00:00', 'Montpellier', 90, 0, 'Termine', 2, 14, NULL, NULL,'Montpellier.png'),
(5, 7, '2020-09-27 14:00:00', 'Pérols', 90, 0, 'Termine', 5, 14, 4, 3,'Pérols.png'),
(8, 6, '2020-09-27 16:00:00', 'Pérols', 90, 0, 'Termine', 8, 14, 7, 6,'Pérols.png'),
(3, 2, '2020-10-12 12:00:00', NULL, NULL, 0, 'Annule', NULL, 14, NULL, NULL, NULL),
(3, 2, '2021-06-12 14:00:00', 'Nîmes', 90, 0, 'En attente', NULL, 14, NULL, NULL, 'Nîmes.png'),
(5, 8, '2021-06-12 16:00:00', 'Nîmes', 90, 0, 'En attente', NULL, 14, NULL, NULL, 'Nîmes.png'),
(3, 5, '2021-06-20 14:00:00', 'Bordeaux', 90, 0, 'En attente', NULL, 14, NULL, NULL, 'Bordeaux.png'),
(1, 5, '2021-02-01 10:00:00', 'Lyon', 55, 0, 'Termine', 5, 7, 3, 5, 'Lyon.png'),
(6, 9, '2021-03-01 11:00:00', 'Lyon', 55, 0, 'Termine', 9, 7, 4, 5, 'Lyon.png'),
(5, 9, '2021-04-01 12:00:00', 'Lyon', 55, 0, 'En attente', NULL, 7, NULL, NULL, 'Lyon.png'),
(1, 5, '2021-02-02 10:00:00', 'Lyon', 55, 0, 'Termine', 5, 13, 3, 5, 'Lyon.png'),
(6, 9, '2021-06-02 11:00:00', 'Lyon', 55, 0, 'En attente', NULL, 13, NULL, NULL, 'Lyon.png'),
(5, 9, '2021-07-02 12:00:00', 'Lyon', 55, 0, 'En attente', NULL, 13, NULL, NULL, 'Lyon.png'),
(1, 2, '2021-08-17 21:30:00', 'Montpellier', 90, 0, 'Termine', 1, 15, 4, 0, 'imgfoot.png'),
(3, 4, '2021-08-17 21:30:00', 'Montpellier', 90, 0, 'Termine', 3, 15, 4, 0, 'imgfoot.png'),
(5, 6, '2021-08-17 21:30:00', 'Montpellier', 90, 0, 'Termine', 5, 15, 4, 0, 'imgfoot.png'),
(7, 8, '2021-08-17 21:30:00', 'Montpellier', 90, 0, 'Termine', 7, 15, 4, 0, 'imgfoot.png');

#Arbre
#dans le tournoi 6, équipes inscrites: 1 2 3 9
#hauteur 0 = racine (finale)
#hauteur 1 = demie finale (quatre équipes dans le tournoi, 2 demies-finales, une finale)
INSERT INTO Arbre (Tournoi, Equipe1, Equipe2, Rencontre, Hauteur, PereGauche, PereDroit, Fils) VALUES 
(6, 1, 3, NULL, 1, NULL, NULL, 3),
(6, 2, 9, NULL, 1, NULL, NULL, 3),
(6, 1, 9, NULL, 0, 1, 2, NULL),
(11, 1, 3, 11, 2, NULL, NULL, 8),
(11, 4, 2, 12, 2, NULL, NULL, 8),
(11, 5, 7, 13, 2, NULL, NULL, 9),
(11, 8, 6, 14, 2, NULL, NULL, 9),
(11, 3, 2, 16, 1, 4, 5, 10),
(11, 5, 8, 17, 1, 6, 7, 10),
(11, 3, 5, 18, 0, 8, 9, NULL),
(12, 1, 5, 19, 1, NULL, NULL, 13),
(12, 6, 9, 20, 1, NULL, NULL, 13),
(12, 5, 9, 21, 0, 11, 12, NULL),
(14, 1, 3, 22, 2, NULL, NULL, 18),
(14, 4, 2, 23, 2, NULL, NULL, 18),
(14, 5, 7, 24, 2, NULL, NULL, 19),
(14, 8, 6, 25, 2, NULL, NULL, 19),
(14, 3, 2, 27, 1, 14, 15, 20),
(14, 5, 8, 28, 1, 16, 17, 20),
(7, 1, 5, 30, 1, NULL, NULL, 23),
(7, 6, 9, 31, 1, NULL, NULL, 23),
(7, 5, 6, 32, 0, 21, 22, NULL),
(13, 1, 5, 33, 1, NULL, NULL, 26),
(13, 6, 9, 34, 1, NULL, NULL, 26),
(13, NULL, NULL, 35, 0, 24, 25, NULL),
(15, 1, 2, 36, 2, NULL, NULL, NULL),
(15, 3, 4, 37, 2, NULL, NULL, NULL),
(15, 5, 6, 38, 2, NULL, NULL, NULL),
(15, 7, 8, 39, 2, NULL, NULL, NULL);




ALTER TABLE Championnat ENGINE=InnoDB;
ALTER TABLE Utilisateur ENGINE=InnoDB;
ALTER TABLE Tournoi ENGINE=InnoDB;
ALTER TABLE Rencontre ENGINE=InnoDB;
ALTER TABLE ListeJoueur ENGINE=InnoDB;
ALTER TABLE ListeEquipe ENGINE=InnoDB;
ALTER TABLE Joueur ENGINE=InnoDB;
ALTER TABLE Equipe ENGINE=InnoDB;
ALTER TABLE Arbre ENGINE=InnoDB;
