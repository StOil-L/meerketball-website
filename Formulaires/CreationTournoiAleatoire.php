<?php
session_start();
include("../CadreStatique/connexion.php");
date_default_timezone_set('Europe/Paris');

try {
	$conn=new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8",$user,$pass,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
}
catch(PDOException $e){
	echo $e->getMessage();
	die("connexion Impossible !");
}

$titreOnglet="Création";
$linkPage="../CadreStatique/CreationTournoiAleatore.php";
$titrePage="Création aléatoire de tournois";

?>
<!doctype html>
                    <?php
                    include("../CadreStatique/skel1.php");
                    ?>
<h1>Meerkat - Tournoi aléatoire</h1>

<p id = "contenu">

<!--
Dans le but d'effectuer une démonstration de l'application, une fonctionnalité permettra de remplir aléatoirement les équipes participant à un tournoi et les scores des rencontres.

-> Initialisation:
	X-> Insérer un nouveau tournoi à 8 participants
	X-> Insérer les 8 participants dans l'arbre
-> Rencontres aléatoires
	X-> Créer les rencontres correspondantes
	X-> Rajouter les rencontres à l'arbre
	X-> Donner un score aléatoire à chaque rencontre (pas d'égalité)
	X-> insérer les x/2 participants dans l'arbre à l'embranchement suivant
	X-> afficher le résultat sous forme de liste
	
-> Améliorations possibles:
	X-> Rendre aléatoire la sélection des équipes qui participent
	X-> Insertion d'une centaine d'équipes pour rendre la sélection aléatoire possibles
	X-> Un champ demandant combien d'équipes pour le test
	X-> Un champ demandant le nom de l'équipe
	X-> Automatiser l'affichage du tournoi
		X-> bouton tout en haut pour l'afficher (envoie l'IdTournoi)
		X-> div prenant une taille d'écran variable (nbEquipes)
		X-> bouton X pour fermer le div
		X-> poser des div pour chaque rencontre

-->

<?php

//section qui insère 100 nouvelles équipes si il y en a moins de 100 insérées
if(!isset($_SESSION['historique']['insertion100equipes'])) {
	$_SESSION['historique']['insertion100equipes'] = true;
	
	$sql = "SELECT COUNT(*) AS total FROM Equipe";
	foreach($conn->query($sql) as $row) {
		$nbEquipeBDD = $row['total'];
	}
	//echo "<p>Equipes dans la bdd: $nbEquipeBDD</p>";
	
	if($nbEquipeBDD < 100) {
		//echo "<p>Insertion de cent équipes</p>";
		for($i=1; $i<=100; $i++) {
			$sql = "INSERT INTO Equipe (Categorie, Nom, Niveau, Mail, Telephone, Images) VALUES ('Poussin', 'Equipe " . $i . "', 5, NULL, NULL, 'equipeinseree.png')";
			$conn->query($sql);
		}
	}
	
	$sql = "SELECT COUNT(*) AS total FROM Equipe";
	foreach($conn->query($sql) as $row) {
		$nbEquipeBDD = $row['total'];
	}
	//echo "<p>Equipes dans la bdd: $nbEquipeBDD</p>";
}

//initialisation
if(!isset($_GET['nom_equipe']) && !isset($_GET['afficherTournoi'])) {	
	//formulaire de choix de création d'équipe
	echo "<form method='GET'><p>Nom de l'équipe: <input type='text' name='nom_equipe' value='Tournoi-test' onFocus=\"this.value='';\"></p>";
	echo "<p>Nombre d'équipes:<select name='nombre_equipe'><option>4</option><option>8</option><option>16</option><option>32</option><option>64</option></select>";
	echo "<p><input type='submit' value='creation'></p></form>";
	
} else if(isset($_GET['nom_equipe'])) {
	$_SESSION['historique']['nom_equipe'] = $_GET['nom_equipe'];
	$_SESSION['historique']['nombre_equipe'] = $_GET['nombre_equipe'];
	$nomEquipe = $_GET['nom_equipe'];
	$nbEquipe = $_GET['nombre_equipe'];	
	
	//insérer un nouveau tournoi
	$sql = "INSERT INTO Tournoi (IdGestionnaire, Nom, DatesDebut, Duree, Lieu, NbEquipeMax, TypeT, Images) VALUES (0, '$nomEquipe', NOW(), 1, 'Hic et Nunc', $nbEquipe, 'Coupe', NULL)";
	
	if($conn->query($sql) === false) {
		echo "Problème lors de l'insertion du tournoi <br>";
	} else {
		$sql = "SELECT LAST_INSERT_ID() AS Id";
		foreach($conn->query($sql) as $row) {
			$IdTournoi = $row['Id'];
		}
		
		//bouton afficher tournoi
		echo "<form methode='GET' action='AffichageTournoi.php'><input type='hidden' name='afficherTournoi' value=$IdTournoi><input type='submit' value='Afficher le tournoi'></form>";
			
		echo "<p>Tournoi $nomEquipe : $nbEquipe équipes participantes</p>";
		echo "IdTournoi: " . $IdTournoi . "<br>";
	
		//insérer les x équipes dans ListeEquipe
		//on requete tous les id d'équipe existants
		//on les mets dans une liste
		//on crée une seconde liste qui sélectionne aléatoirement les id
		//on insère les données de la seconde liste
		$idEquipeArray = array();
		$sql = "SELECT IdEquipe FROM Equipe";
		$i = 0;
		foreach($conn->query($sql) as $row) {
			$i++;
			$idEquipeArray[$i] = $row['IdEquipe'];
		}
		//on sauvegarde le nombre total d'équipes dans la Bdd
		$nbEquipeBDD = $i;
		$nb = 0;
		$ListeEquipe = array();
		for($i = 1; $i <= $nbEquipe; $i++) {
			//on tire une equipe aléatoirement dans la liste
			do {
				$indice = rand(1, $nbEquipeBDD);				
			} while ($idEquipeArray[$indice] == -1);
			$ListeEquipe[$i] = $idEquipeArray[$indice];
			//on retire l'équipe de la liste
			$idEquipeArray[$indice] = -1;
			
			$sql = "INSERT INTO ListeEquipe (IdEquipe, IdTournoi, AttenteInscription, DateInscription) VALUES (" . $ListeEquipe[$i] . "," . $IdTournoi . ", 0, NOW())";
			if($conn->query($sql) != false)
				$nb++;
		}
		echo $nb . " équipes correctement insérées dans ListeEquipe. <br>";
		
		//insérer les participants dans l'arbre
		//coupler aléatoirement les équipes deux à deux
		$Equipe1 = 0;
		$Equipe2 = 0;
		$IdRand = 1;
		//calculer la hauteur de démarrage
		$Hauteur = log($nbEquipe, 2) - 1;
		
		for($i = 1; $i <= ($nbEquipe/2); $i++) {
			//on choisit au hasard deux équipes qui n'ont pas encore été choisies
			while($ListeEquipe[$IdRand = rand(1,$nbEquipe)] == 0) {}
			$Equipe1 = $ListeEquipe[$IdRand];
			$ListeEquipe[$IdRand] = 0;
			while($ListeEquipe[$IdRand = rand(1,$nbEquipe)] == 0) {}
			$Equipe2 = $ListeEquipe[$IdRand];
			$ListeEquipe[$IdRand] = 0;
			
			//on insère les deux équipes dans un nouveau noeud de l'arbre
			$sql = "INSERT INTO Arbre (Tournoi, Equipe1, Equipe2, Rencontre, Hauteur, PereGauche, PereDroit, Fils) VALUES (" . $IdTournoi . "," . $Equipe1 . "," . $Equipe2 . ", NULL, $Hauteur, NULL, NULL, NULL)";
			if($conn->query($sql) != false)
				echo "Noeud " . $i . " créé. <br>";			
		}
		
		//pour chaque hauteur de l'arbre, créer les rencontres, associer un score aléatoire (non égalitaire), créer les noeuds au dessus
		while($Hauteur != -1) {
			echo "Hauteur: " . $Hauteur . "<br>";
			
			//sélectionner les noeuds à la bonne hauteur
			$sql = "SELECT IdArbre, Equipe1, Equipe2 FROM Arbre WHERE Tournoi = " . $IdTournoi . " AND Hauteur = " . $Hauteur;
			$result = $conn->query($sql);
			
			//on sauvegarde la liste des vainqueurs pour les noeuds suivants
			$ListeVainqueurs = array();
			$index = 1;
			
			foreach($result as $row) {
				//créer deux score aléatoires
				$score1 = rand(0,16);
				while(($score2 = rand(0,16)) == $score1) {}
				if($score1 > $score2) 
					$Vainqueur = $row['Equipe1'];
				else
					$Vainqueur = $row['Equipe2'];
				
				$ListeVainqueurs[$index] = $Vainqueur;
				$index++;
				
				//insérer les valeurs en créant une rencontre
				if($conn->query("INSERT INTO Rencontre (Equipe1, Equipe2, DateRencontre, Lieu, Duree, TempsAdditionnel, EtatRencontre, Vainqueur, Tournoi, ScoreEquipe1, ScoreEquipe2, Images) VALUES (" . $row['Equipe1'] . "," . $row['Equipe2'] . ", '2018-08-17 14:30:00', 'Hic et Nunc', 90, 0, 'Termine'," . $Vainqueur . "," . $IdTournoi . "," . $score1 . "," . $score2 . ", NULL)") === false)
					echo $conn->error;
				
				foreach($conn->query("SELECT LAST_INSERT_ID() AS id") as $throwaway) {
					$lastid = $throwaway['id'];
				}
				
				//actualiser le noeud pour y ajouter la rencontre
				$conn->query("UPDATE Arbre SET Rencontre = " . $lastid . " WHERE IdArbre = " . $row['IdArbre']);
				
				echo "Rencontre" . $lastid . " - équipe" . $row['Equipe1'] . " - équipe" . $row['Equipe2'] . " : " . $score1 . "-" . $score2 . " --- Vainqueur: équipe" . $Vainqueur . "<br>";
			}
			
			//si on est à la racine le travail est terminé
			if($Hauteur != 0) {
				//on crée les noeuds au dessus
				for($i = 1; $i <= ($index-1)/2; $i++) {
					//on cherche les deux noeuds parents
					foreach($conn->query("SELECT IdArbre FROM Arbre WHERE Tournoi = " . $IdTournoi . " AND Hauteur = " . $Hauteur . " AND ( Equipe1 = " . $ListeVainqueurs[2*$i-1] . " OR Equipe2 = " . $ListeVainqueurs[2*$i-1] . ")") as $throw) {
						$Parent1 = $throw['IdArbre'];
					}
					foreach($conn->query("SELECT IdArbre FROM Arbre WHERE Tournoi = " . $IdTournoi . " AND Hauteur = " . $Hauteur . " AND ( Equipe1 = " . $ListeVainqueurs[2*$i] . " OR Equipe2 = " . $ListeVainqueurs[2*$i] . ")") as $throw) {
						$Parent2 = $throw['IdArbre'];
					}					
					//on insère le nouveau noeud
					$conn->query("INSERT INTO Arbre (Tournoi, Equipe1, Equipe2, Rencontre, Hauteur, PereGauche, PereDroit, Fils) VALUES (" . $IdTournoi . "," . $ListeVainqueurs[2*$i-1] . "," . $ListeVainqueurs[2*$i] . ", NULL," . ($Hauteur-1) . "," . $Parent1 . "," . $Parent2 . ", NULL)");
					 
					foreach($conn->query("SELECT LAST_INSERT_ID() AS id") as $throw) {
						$lastid = $throw['id'];
					}
					
					//on update les noeuds parents
					$conn->query("UPDATE Arbre SET Fils = " . $lastid . " WHERE IdArbre = " . $Parent1);
					$conn->query("UPDATE Arbre SET Fils = " . $lastid . " WHERE IdArbre = " . $Parent2);
				}
			}
			$Hauteur--;
		}
		
	}
} 


?>
</p>
                    <?php
                    include("../CadreStatique/skel2.php");
                    ?>
</html>
