<?php
session_start();
include("connexion.php");
date_default_timezone_set('Europe/Paris');

try {
	$dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
	echo $e->getMessage();
	die("connexion Impossible !");
}

$titreOnglet = "Gestion Tournoi";
$linkPage = "../Formulaires/GestionTournoi.php";
$titrePage = "Gestion Tournoi";

?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");
?>

<style>
#blocP {
	height: auto;
	overflow-x: auto;
	overflow-y: auto;
  	position: relative;
}  
.tooltip2 {
	position: relative;
	display: inline-block;
	width: 100%;
	height: 100%;
	background-color: transparent;
}
  
.tooltip2 .tooltiptext2 {
	visibility: hidden;
	width: 200px;
	background-color: #555;
	color: #fff;
	text-align: center;
	padding: 5px 0;
	border-radius: 6px;
	  
	/* Position du texte */
	position: absolute;
	z-index: 99;
	left: 450%;
	top: -50%;
	margin-left: -60px;
	  
	/* tooltip moins abrupt */
	opacity: 0;
	transition: opacity 0.5s;
}
 
.tooltip2:hover .tooltiptext2 {
	visibility: visible;
	opacity: 1;
}
  
.hidden {
	display: none;
}
.nothidden {
	display: inline-block;
}
.onglet {
	position: absolute;
	height: 50px;
	width: 9%;
	border: 1px solid blue;
	border-radius: 5px;
	margin: 15px 0;
    z-index: 5000;
}
.surligne {
	background-color: grey;
}
.contenuGestionTournoi {
	position: absolute;
	width: 90%;
	left: 5%;
	border: 1px solid black;
	border-radius: 5px;
	overflow-y: auto;
	overflow-x: auto;
    margin-top: 80px;
    margin-left: -12px;
	padding: 5px;
	height: 85%;
}
#onglet1 {
	left: 5%;
}
#onglet2 {
	left: 15%;
}
#onglet3 {
	left: 25%;
}
#onglet4 {
	left: 35%;
}
#onglet5 {
	left: 45%;
}
#wrapperGestion {
	position: relative;
}
#MenuDeroulantTournoi {
	position: absolute;
	right: 10%;
	top: 20px;
}
</style>


<?php
if(isset($_SESSION['historique']['login'])){
$login=$_SESSION['historique']['login'];

$sql = "SELECT Tournoi.Nom, Tournoi.IdTournoi, Tournoi.TypeT FROM Tournoi, Utilisateur WHERE (IdGestionnaire=IdUtilisateur OR RoleU='Administrateur') AND LoginU='$login' ";
echo "<form method=post name='MenuDeroulantTournoi' id='MenuDeroulantTournoi'>";
echo "<select name='IdTournoiAmont' onchange='MenuDeroulantTournoi.submit()'><option value=''>Veuillez choisir un tournoi</option>";
foreach ($dbh->query($sql) as $row) {
	if($row['TypeT']=='Coupe'){
		$row['TypeT']='Tournoi à élimination directe';
	}
	else if ($row['TypeT']=='PlusieursTours'){
		$row['TypeT']='Tournoi aller-retour';
	}
	echo "<option value='" . $row['IdTournoi'] . "'>" . $row['IdTournoi'] . " : " . $row['Nom'] . " (" . $row['TypeT'] . ")</option>";
}


echo "</select></form>";
}
else echo "<meta http-equiv='refresh' content='0;URL=LoginUtilisateur.php?redirection=GestionTournoi.php'/>";

?>
<?php
/*
------------------------
Onglets navigation
------------------------
*/
if(isset($_POST['IdTournoiAmont'])){
	$_SESSION['historique']['RencontresIdTournoi']=$_POST['IdTournoiAmont'];
}
if(isset($_SESSION['historique']['RencontresIdTournoi'])){
	

	if(! (isset($_POST['equipesAValider']) || isset($_POST['RencontreValidation'])  || isset($_POST['score1'], $_POST['score2']) || isset($_POST['PasserTourSuivant']) || isset($_POST['DemarrerTournoi']))) {
		echo "<div class='onglet surligne' id='onglet1' onClick='onglet(1)'>Synthèse Gestion</div>";
	} else {
		echo "<div class='onglet' id='onglet1' onClick='onglet(1)'>Synthèse Gestion</div>";
	}
	if((isset($_POST['equipesAValider'])) && !isset($_POST['Synthese'])) {
		echo "<div class='onglet surligne' id='onglet2' onClick='onglet(2)'>Validation Equipes</div>";
	} else {
		echo "<div class='onglet' id='onglet2' onClick='onglet(2)'>Validation Equipes</div>";
	}
	if((isset($_POST['RencontreValidation'])) && !isset($_POST['Synthese'])) {
		echo "<div class='onglet surligne' id='onglet3' onClick='onglet(3)'>Gérer Rencontres</div>";
	} else {
		echo "<div class='onglet' id='onglet3' onClick='onglet(3)'>Gérer Rencontres</div>";
	}
	if((isset($_POST['score1'], $_POST['score2'])) && !isset($_POST['Synthese'])) {
		echo "<div class='onglet surligne' id='onglet4' onClick='onglet(4)'>Rentrer Scores</div>";
	} else {
		echo "<div class='onglet' id='onglet4' onClick='onglet(4)'>Rentrer Scores</div>";
	}
	if((isset($_POST['PasserTourSuivant']) || isset($_POST['DemarrerTournoi'])) && !isset($_POST['Synthese'])) {
		echo "<div class='onglet surligne' id='onglet5' onClick='onglet(5)'>Stade Tournoi</div>";
	} else {
		echo "<div class='onglet' id='onglet5' onClick='onglet(5)'>Stade Tournoi</div>";
	}
	echo "<div class='contenuGestionTournoi'>";

}

?>
<div id="wrapperGestion" class="container">
<?php

/*
------------------------
Fonctions utiles
------------------------
*/

if(isset($_SESSION['historique']['RencontresIdTournoi'])){
	function hauteurEnMots($hauteurParam) {
		switch ($hauteurParam) {
			case 0:
				return "finale";
				break;
			case 1:
				return "demie-finale";
				break;
			case 2:
				return "quart de finale";
				break;
			case 3:
				return "huitième de finale";
				break;
			case 4:
				return "seizième de finale";
				break;
			case 5:
				return "32eme de finale";
				break;
			case 6:
				return "64eme de finale";
				break;
			case 7:
				return "128eme de finale";
				break;
			default: 
				return "erreur fonction hauteurEnMots()";
				break;
		}
	}


	/*
	------------------------
	Vérification Login
	------------------------
	*/


	/*
	-----------------------------------------------------------------
	Equipes en attente de validation
	-----------------------------------------------------------------
	*/

	if (isset($_POST['equipesAValider']) && !isset($_POST['Synthese'])) {
		echo "<div class='nothidden' id='GestionValidation'>";
	} else {
		echo "<div class='hidden' id='GestionValidation'>";
	}
	echo "<h2>Equipes en attente de validation</h2>";

	/*
	------------------
	Validation activée
	*/
	if (isset($_POST['equipesAValider'])) {
		if (isset($_POST['checkboxCocheesV'])) {
			foreach ($_POST['equipesAValider'] as $equipe) {
				$idTournoi = substr($equipe, 0, 9);
				$idEquipe = ltrim(substr($equipe, 10));
				$dbh->exec("UPDATE ListeEquipe, Equipe SET AttenteInscription = 0 WHERE Equipe.IdEquipe=ListeEquipe.IdEquipe AND  Equipe.IdEquipe= '$idEquipe' AND ListeEquipe.IdTournoi=$idTournoi ");
			}
		} else if (isset($_POST['checkboxCocheesR'])) {
			foreach ($_POST['equipesAValider'] as $equipe) {
				$idTournoi = substr($equipe, 0, 9);
				$idEquipe = ltrim(substr($equipe, 10));
				$dbh->exec("DELETE FROM ListeEquipe WHERE IdTournoi=$idTournoi AND IdEquipe=$idEquipe");
			}
		}
	}

	/*
	------------------
	*/

	//afficher les tournois et leur nombre d'équipes en attente de validation
	$idTournoiC=$_SESSION['historique']['RencontresIdTournoi'];
	$sql = "SELECT COUNT(*) AS Nombre, t1.IdTournoi, t1.Nom, t1.NbEquipeMax  FROM ListeEquipe le1, Tournoi t1, Utilisateur WHERE LoginU='$login' AND t1.IdTournoi=le1.IdTournoi AND AttenteInscription = 0 AND (IdUtilisateur = IdGestionnaire OR RoleU = 'Administrateur') AND t1.EtatTournoi = 'Phase Inscription' AND t1.IdTournoi=$idTournoiC GROUP BY t1.idtournoi";
	$placeRestante=-1;
	foreach ($dbh->query($sql) as $row) {
		if (isset($row['Nombre'], $row['IdTournoi'], $row['Nom'], $row['NbEquipeMax'])) {
			$placeRestante = $row['NbEquipeMax'] - $row['Nombre'];
			echo "id:$row[IdTournoi] -- tournoi  $row[Nom] ($placeRestante places restantes) <br/>   ";
		}
	}
	echo "<br/>";


	$champ='';	
	$verifChamp="SELECT TypeT FROM Tournoi WHERE IdTournoi=$idTournoiC";
	foreach($dbh->query($verifChamp) as $row ){
	
		if($row['TypeT']=='Championnat'){
			$champ=$row['TypeT'];
		}
	}
	
	if($champ!=''){
	
	if($placeRestante==0 ){
		$i='';
		$name=array();
		$k=0;
		$maxEquipe='';
		
		$sql3="SELECT count(IdEquipe) as maxEquipe FROM ListeEquipe WHERE IdTournoi='$idTournoiC' ";
		foreach($dbh->query($sql3) as $row){
	
			$maxEquipe=$row['maxEquipe'];
		}
		$sql2="SELECT Nom, Equipe.IdEquipe FROM Equipe, ListeEquipe WHERE ListeEquipe.IdEquipe= Equipe.IdEquipe AND ListeEquipe.IdTournoi=$idTournoiC";
		foreach($dbh->query($sql2) as $row){
	
			$name[$k]=$row['IdEquipe'];
			$k++;
		}
		$sql="SELECT count(*) as nombreRencontre FROM Championnat WHERE Tournoi='$idTournoiC' ";
		foreach($dbh->query($sql) as $row){
			
			$i=$row['nombreRencontre'];
	
			
		}
		$k=0;
		$l=1;
	$noeudChamp='';
	$journee=1;
	$petitNoeud="SELECT IdChampionnat, Rencontre FROM Championnat WHERE Tournoi= '$idTournoiC' AND IdChampionnat<=(SELECT min(IdChampionnat) FROM Championnat WHERE Tournoi='$idTournoiC')";
	foreach($dbh->query($petitNoeud) as $row){
		$noeudChamp=$row['Rencontre'];
	}
	$j=$noeudChamp+$i;
	While($noeudChamp<$j){
	
			if($k==$l){
				$l++;
			}
			$dbh->exec("UPDATE Rencontre SET Equipe1='$name[$k]' , Equipe2='$name[$l]'   WHERE IdRencontre='$noeudChamp' AND Tournoi='$idTournoiC'");
			
	
			if($l>=$maxEquipe-1){
				$k++;
				$l=0;
				if($k>$l){
				}
			}
			else if($k<=$maxEquipe-1){
				 
				$l++;
			}
			else {
				$k=0;
				$l=1;
			}
		
			$noeudChamp++;
		
		}
				$dbh->exec("UPDATE Tournoi SET EtatTournoi='En cours' WHERE IdTournoi='$idTournoiC'");

	}
	}
		
		
	//afficher les équipes qui attendent la validation

	echo "<form method=post action=GestionTournoi.php>";


	$sql = "SELECT Equipe.IdEquipe, Equipe.Niveau, Equipe.Nom, Tournoi.Nom as nomTournoi, Tournoi.IdTournoi  FROM Equipe, ListeEquipe, Tournoi, Utilisateur WHERE Equipe.IdEquipe=ListeEquipe.IdEquipe AND ListeEquipe.IdTournoi=Tournoi.IdTournoi AND Tournoi.EtatTournoi = 'Phase inscription' AND (IdUtilisateur=IdGestionnaire OR RoleU = 'Administrateur') AND AttenteInscription=1 AND LoginU= '$login' AND Tournoi.IdTournoi=$idTournoiC Group By Equipe.IdEquipe,Tournoi.Nom,Equipe.Nom ";

	$nbEquipesAValider = 0;
	foreach ($dbh->query($sql) as $row) {
		$nbEquipesAValider++;
		if (isset($row['Nom'], $row['nomTournoi'])) {  //Les noms des équipes sans nom ne crée plus de blancs, mais la phrase "équipe sans nom"
			echo "l'équipe : " . $row['Nom'] . "    " . "(niv: " . $row['Niveau'] . ") veut s'inscrire au tournoi : " .  $row['nomTournoi'];
		} else {
			echo "l'équipe : " . ' [équipe sans nom]   ' . "(niv: " . $row['Niveau'] . ") veut s'inscrire au tournoi : " . $row['nomTournoi'];
		}
		echo "<div>
				<input type='checkbox'  name='equipesAValider[]' value='" . $row['IdTournoi'] . "           " . $row['IdEquipe'] . "' >
			</div>";
	}

	if ($nbEquipesAValider > 0) {
		//bouton pour valider les équipes dont les cases ont été cochées. 
		echo "<div> 
				<button type='submit' class='click' name='checkboxCocheesV'  value=  > Valider ces équipes </button> 
			</div>";
		//bouton pour les rejeter
		echo "<div>
				<button type='submit' class='click' name='checkboxCocheesR' value= > Rejeter ces équipes </button>
			</div>";
	} else {
		echo "Pas d'équipe en attente de validation<br/></form>";
	}

	echo "</form>";


	/*
	------------------
	Validation activée
	*/

	if (isset($_POST['equipesAValider'])) {
		echo "<br/><div class='alert alert-success'>Vos choix ont été validés !</div>";
	}

	if (!isset($_POST['equipesAValider'])) {
		if ((isset($_POST['checkboxCocheesV']) && $_POST['checkboxCocheesV'] == '') || (isset($_POST['checkboxCocheesR']) && $_POST['checkboxCocheesR'] == '')) {
			echo "<br/><div class='alert alert-danger'>Vous n'avez saisi aucun choix !</div>";
		}
	}

	/*
	------------------
	*/

	echo "</div>";

	/*
	----------------------------------------------------
	Gestion des rencontres
	----------------------------------------------------
	*/

	if((isset($_POST['RencontreValidation']) || isset($_POST['RencontresIdTournoi'])) && !isset($_POST['Synthese'])) {
		echo "<div class='nothidden' id='GestionRencontre'>";
	} else {
		echo "<div class='hidden' id='GestionRencontre'>";
	}
	echo "<h2> Gestion des rencontres</h2> ";

	/*
	----------------------------------------
	sélectionner un tournoi
	*/

	

	/*
	----------------------------------------
	Validation des rencontres
	*/

	if (isset($_POST['RencontreValidation'])) {
		//ici il faudrait protéger un peu plus la réception des données
		$DateRencontre = $_POST['DateRencontre'];
		if ($DateRencontre == null) {
			$DateRencontre = "1970-01-01";
		}
		$Lieu = $_POST['Lieu'];
		if ($Lieu == null) {
			$Lieu = "Hic et Nunc";
		}
		$Duree = $_POST['Duree'];
		if ($Duree == null) {
			$Duree = 90;
		}
		$Images = $_POST['Images'];
		if ($Images == null) {
			$Images = "default.png";
		}
		$TempsAdditionnel = $_POST['TempsAdditionnel'];
		if($TempsAdditionnel == null) {
			$TempsAdditionnel = 0;
		}
		$IdArbre = $_POST['IdArbre'];
		$Equipe1 = $_POST['Equipe1'];
		$Equipe2 = $_POST['Equipe2'];
		$Rencontre = $_POST['RencontreValidation'];

		$IdTournoi = $_SESSION['historique']['IdTournoiAmont'];

		if ($Rencontre == -1) {
			//enregistrer la nouvelle rencontre
			$dbh->query("INSERT INTO Rencontre (Tournoi, DateRencontre, Lieu, Duree, Images, Equipe1, Equipe2, TempsAdditionnel) VALUES ($IdTournoi, '$DateRencontre', '$Lieu', $Duree, '$Images', $Equipe1, $Equipe2, $TempsAdditionnel)");
			foreach ($dbh->query("SELECT last_insert_id() AS Id") as $row) {
				$lastId = $row['Id'];
			}
			//update l'arbre
			$dbh->query("UPDATE Arbre SET Rencontre = $lastId WHERE IdArbre = $IdArbre");
		} else {
			//modifier une rencontre existante
			$dbh->query("UPDATE Rencontre SET DateRencontre = '$DateRencontre', Lieu = '$Lieu', Duree = $Duree, Images = '$Images', TempsAdditionnel = $TempsAdditionnel WHERE IdRencontre = $Rencontre");
		}
	}

	/*
	----------------------------------------
	Afficher les rencontres à modifier/créer
	-> afficher Equipe1/Equipe2
	-> champs DateRencontre, Lieu, Durée, Images (plus tard, ici, un dépot d'image?)
	*/

	//on veut réafficher le même menu si on vient de valider une rencontre
	if (isset($_POST['IdTournoiAmont']) || isset($_SESSION['historique']['IdTournoiAmont'])) {
	if (isset($_POST['IdTournoiAmont'])) {
		$IdTournoi = $_POST['IdTournoiAmont'];
		$_SESSION['historique']['IdTournoiAmont'] = $IdTournoi;
	} else {
		$IdTournoi = $_SESSION['historique']['IdTournoiAmont'];
	}

	//Afficher le nom du tournoi sélectionné
	foreach ($dbh->query("SELECT Nom FROM Tournoi WHERE IdTournoi = $IdTournoi") as $row) {
		$nomTournoi = $row['Nom'];
	}
	echo "<h3>$nomTournoi</h3>";

	
	$sql="SELECT TypeT FROM Tournoi WHERE IdTournoi=$IdTournoi";

	foreach ($dbh->query($sql) as $row){
		$champ=$row['TypeT'];	
	}
	//Sélectionner les IdArbre contenant des rencontres vides 
	$rencontresVides = $dbh->prepare("SELECT IdArbre, Equipe1, Equipe2, e1.nom AS nom1, e2.nom AS nom2 FROM Arbre, Equipe e1, Equipe e2 WHERE Arbre.Tournoi = $IdTournoi AND Hauteur = (SELECT MIN(Hauteur) FROM Arbre WHERE Arbre.Tournoi = $IdTournoi) AND e1.IdEquipe = Equipe1 AND e2.IdEquipe = Equipe2 AND Arbre.Rencontre IS NULL");
	$rencontresVides->execute();	
	if(isset($champ)){
		if($champ!='Championnat'){
	
	if (!empty($rencontresVides)) {
		echo "Ajout nécessaire de nouvelles rencontres: <br/><br/>";
		foreach ($rencontresVides as $row) {
			echo "<div class='alert alert-danger'>";
			echo "[" . $row['nom1'] . "] VS [" . $row['nom2'] . "] <br/>";
			echo "<form method=post>
					Date de la rencontre : <input type='date' name='DateRencontre' value='2021-01-01'><br/>
					Lieu : <input type='text' name='Lieu' value='Hic et Nunc'><br/>
					Durée (minutes) : <input type='number' name='Duree' value=90 min=0>
					Image : <input type='text' name='Images' value='default.png'><br/>
					Temps Additionnel : <input type='number' name='TempsAdditionnel' value=0 min=0><br/>
					<input type='hidden' name='IdArbre' value=" . $row['IdArbre'] . ">
					<input type='hidden' name='Equipe1' value=" . $row['Equipe1'] . ">
					<input type='hidden' name='Equipe2' value=" . $row['Equipe2'] . ">
					<input type='hidden' name='RencontreValidation' value=-1>
					<input type='submit' class='click-ligne' value='Valider'>
				</form></div>";
		}
	}

	//sélectionner toutes les autres rencontres	
	$rencontresCrees = $dbh->prepare("SELECT IdRencontre, Rencontre.Equipe1 AS Equipe1, Rencontre.Equipe2 AS Equipe2, IdArbre, e1.Nom AS nom1, e2.Nom AS nom2, DateRencontre, Lieu, Duree, Rencontre.Images AS Images , TempsAdditionnel FROM Arbre, Equipe e1, Equipe e2, Rencontre WHERE Arbre.Tournoi = $IdTournoi AND Hauteur = (SELECT MIN(Hauteur) FROM Arbre WHERE Arbre.Tournoi = $IdTournoi) AND e1.IdEquipe = Rencontre.Equipe1 AND e2.IdEquipe = Rencontre.Equipe2 AND Arbre.Rencontre = IdRencontre AND e1.Niveau <> -1 AND e2.Niveau <> -1");
	$rencontresCrees->execute();
	if (!empty($rencontresCrees)) {
		echo "<br/>Modifier des rencontres: <br/><br/>";
		foreach ($rencontresCrees as $row) {
			echo "<div class='alert alert-success'>";
			echo "[" . $row['nom1'] . "] VS [" . $row['nom2'] . "] <br/>";
			echo "<form method=post>
					Date de la rencontre : <input type='date' name='DateRencontre' value=" . $row['DateRencontre'] . "><br/>
					Lieu : <input type='text' name='Lieu' value='" . $row['Lieu'] . "'><br/>
					Durée (minutes) : <input type='number' name='Duree' value=" . $row['Duree'] . "><br/>
					Image : <input type='text' name='Images' value='" . $row['Images'] . "'><br/>
					Temps Additionnel (minutes): <input type='number' name='TempsAdditionnel' value=" . $row['TempsAdditionnel'] . "><br/>
					<input type='hidden' name='IdArbre' value=" . $row['IdArbre'] . ">
					<input type='hidden' name='Equipe1' value=" . $row['Equipe1'] . ">
					<input type='hidden' name='Equipe2' value=" . $row['Equipe2'] . ">
					<input type='hidden' name='RencontreValidation' value=" . $row['IdRencontre'] . ">
					<input type='submit' class='click-ligne' value='Modifier'>
					</form></div>";
		}
	}
	}

else echo "Les championnats se génèrent automatiquement lorsque toutes les équipes ont été inscrites.";
	}
	}
	echo "</div>";

	/*
	----------------------------------------------------
	Gestion des scores
	----------------------------------------------------
	*/

	if((isset($_POST['score1'], $_POST['score2']))  && !isset($_POST['Synthese'])) {
		echo "<div class='nothidden' id='GestionScore'>";
	} else {
		echo "<div class='hidden' id='GestionScore'>";
	}
	echo "<h2> Gestion des Scores </h2>";


	/*
	-----------------
	Validation scores
	*/


	if (isset($_POST['score1'], $_POST['score2'])) {
		if ($_POST['score1'] == '' or $_POST['score2'] == '') {
			echo "<div class='alert alert-danger'>Le score saisi est incomplet </div>";
		} else if($_POST['score1'] == $_POST['score2']) {
			echo "<div class='alert alert-danger'>Impossible de saisir un ex-aequo</div>";
		} else {
			//enregistrer le score
			if($_POST['score1'] > $_POST['score2']) {
				$vainqueur = $_POST['Equipe1'];
			} else {
				$vainqueur = $_POST['Equipe2'];
			}
			$dbh->exec("UPDATE Rencontre SET ScoreEquipe1=" . $_POST['score1'] . ", ScoreEquipe2=" . $_POST['score2'] . ", Vainqueur=" . $vainqueur . ", EtatRencontre='Termine' WHERE IdRencontre=" . $_POST['IdRencontreScore']);
			echo "<div class='alert alert-success'>Score Validé </div>";
			
			//vérifier si tous les scores d'une hauteur en cours sont enregistrés
			$sql = "SELECT COUNT(Vainqueur) AS nbVainqueurs, MIN(HAUTEUR) AS hauteurMin FROM Rencontre, Arbre WHERE Arbre.Rencontre = IdRencontre AND Hauteur = (SELECT MIN(Hauteur) FROM Arbre WHERE Arbre.Tournoi = " . $_POST['IdTournoi'] . ") AND Rencontre.Tournoi = " . $_POST['IdTournoi'];
			foreach($dbh->query($sql) as $row) {
				$nbVainqueurs = $row['nbVainqueurs'];
				$hauteur = $row['hauteurMin'];
			}
			$nbMatchRestants = pow(2, $hauteur) - $nbVainqueurs;
			//echo "matchs restants: " . $nbMatchRestants . " / hauteur: " . $hauteur . " / nbVainqueurs: " . $nbVainqueurs;
			if($nbMatchRestants == 0) {
				$_POST['PasserTourSuivant'] = $_POST['IdTournoi'];
			}
		}
	}

	/*
	-----------------
	*/
	$sql="SELECT TypeT FROM Tournoi WHERE IdTournoi=$IdTournoi";

	foreach ($dbh->query($sql) as $row){
		$champ=$row['TypeT'];	
		}
	if( $champ != 'Championnat'){
		$sql = "SELECT Tournoi.IdTournoi, ScoreEquipe1, ScoreEquipe2, e1.IdEquipe AS IdEquipe1, e2.IdEquipe AS IdEquipe2, e1.Nom AS team1, e2.Nom AS team2, Tournoi.Nom AS nom, IdRencontre, Hauteur FROM Equipe e1, Equipe e2, Arbre, Tournoi, Rencontre, Utilisateur WHERE Rencontre.Tournoi=Tournoi.IdTournoi AND (IdGestionnaire=IdUtilisateur OR RoleU = 'Administrateur') AND Rencontre.Equipe1 = e1.IdEquipe AND Rencontre.Equipe2 = e2.IdEquipe AND LoginU = '$login' AND (ScoreEquipe1 IS NULL OR ScoreEquipe2 IS NULL) AND Arbre.Rencontre = Rencontre.IdRencontre AND e1.Niveau <> -1 AND e2.Niveau <> -1 AND Tournoi.IdTournoi=$idTournoiC GROUP BY IdRencontre ORDER BY Tournoi.Nom";
	
		$nbScores = 0;
		$nomTournoi = '';
		foreach ($dbh->query($sql) as $row) {
	
			if (isset($row['team1'], $row['team2'], $row['nom'])) {
				$tournoi = $row['nom'];
	
				if ($tournoi != $nomTournoi) { //afficher nom du tournoi
					echo  "<br/>" . "tournoi : " . $tournoi . "<br/></br>";
					$nomTournoi = $tournoi;
				}
				
				echo "[" . $row['team1'] . "] VS [" . $row['team2'] . "] ---- " . hauteurEnMots($row['Hauteur']) . "<br/>";
				echo "<form method=post action='GestionTournoi.php'>";
	
				echo "Score :";
				if ($row['ScoreEquipe1'] == NULL) {
					echo "<input type= 'number' size='3' name=score1 min=0>";
				} else {
					echo "<input type= 'number' size='3' name=score1 value=" . $row['ScoreEquipe1'] . " min=0>";
				}
				echo " - ";
				if ($row['ScoreEquipe2'] == NULL) {
					echo "<input type= 'number' size='3' name=score2 min=0>";
				} else {
					echo "<input type= 'number' size='3' name=score2 value=" . $row['ScoreEquipe2'] . " min=0>";
				}
				echo "<input type='hidden' name='IdRencontreScore' value=" . $row['IdRencontre'] . ">";
				echo "<input type='hidden' name='Equipe1' value=" . $row['IdEquipe1'] . ">";
				echo "<input type='hidden' name='Equipe2' value=" . $row['IdEquipe2'] . ">";
				echo "<input type='hidden' name='IdTournoi' value=" . $row['IdTournoi'] . ">";
				echo "<input type='submit' class='click' value= 'Valider'/> <br/></form>";
	
				$nbScores++;
			}
		}
	
		if ($nbScores == 0) {
			echo "Aucun score à remplir";
		}
	
		echo "</div>";
	}
	
	 else {
	
		$nomsEquipe=array();
		$sql2="SELECT Equipe.Nom, Equipe.IdEquipe FROM Equipe, Championnat, ListeEquipe, Rencontre WHERE ListeEquipe.IdEquipe=Equipe.IdEquipe AND Equipe1=Equipe.IdEquipe AND Championnat.Tournoi='$idTournoiC'  GROUP BY IdEquipe";
	
		$i='';
	foreach($dbh->query($sql2) as $row){
	
		 $i=$row['IdEquipe'];
		$nomsEquipe[$i]=$row['Nom'];
	}
		$sql="SELECT ScoreEquipe1, ScoreEquipe2, Championnat.Tournoi, Equipe1, Equipe2 , Nom,IdChampionnat, Championnat.Rencontre FROM Championnat , Equipe, Rencontre WHERE Equipe1=idEquipe  AND Championnat.Tournoi=$idTournoiC AND Championnat.Rencontre = Rencontre.IdRencontre AND (ScoreEquipe1 IS NULL OR ScoreEquipe2 IS NULL)  ";
	foreach($dbh->query($sql) as $row){
	
	$equipe1=$row['Equipe1'];
	$equipe2=$row['Equipe2'];
	
	echo "<form method=post action=GestionTournoi.php>
	 $nomsEquipe[$equipe1] VS $nomsEquipe[$equipe2] <br/>
	 <input type=text name=Champscore1 size=3 value=0 required> - <input type=text name=Champscore2 size=3 value=0 required>  
	 <input type='hidden' name=idrencontre value=$row[Rencontre]>
	<button type=submit class='click' name=idChamp value=$row[IdChampionnat]> Valider </button> <br/>
	</form> 
	 ";
	
	}
	$scoreRestant=-1;
	$RemplirScore="SELECT Count(*) as scoreRestant, TypeT FROM Tournoi, Championnat, Rencontre WHERE Championnat.Tournoi=Tournoi.IdTournoi AND Championnat.Tournoi=$idTournoiC AND Championnat.Rencontre = Rencontre.IdRencontre AND ScoreEquipe1 IS NULL";
	
	foreach($dbh->query($RemplirScore) as $row){
	$scoreRestant=$row['scoreRestant'];
	$champ=$row['TypeT'];
	}
	if($scoreRestant==0){
		echo "Aucun Score à saisir.";
		if($champ=='Championnat'){
			$dbh->exec("UPDATE Tournoi SET EtatTournoi='Acheve' WHERE IdTournoi='$idTournoiC'");
		}
	}
	if(isset($_POST['idChamp'])){
		$dbh->exec("UPDATE Rencontre SET ScoreEquipe1=$_POST[Champscore1], ScoreEquipe2=$_POST[Champscore2] WHERE IdRencontre=$_POST[idrencontre] ");
	
	if($_POST['Champscore1']>$_POST['Champscore2']){
		$dbh->exec("UPDATE Championnat SET Resultat=-1 WHERE IdChampionnat=$_POST[idChamp]");
	}
	else if($_POST['Champscore1']<$_POST['Champscore2']){
		$dbh->exec("UPDATE Championnat SET Resultat=1 WHERE IdChampionnat=$_POST[idChamp]");
	}
	else if($_POST['Champscore1']==$_POST['Champscore2']){
		$dbh->exec("UPDATE Championnat SET Resultat=0 WHERE IdChampionnat=$_POST[idChamp]");
	}
	echo "<meta http-equiv='refresh' content='0;URL=GestionTournoi.php?redirection=GestionTournoi.php'/>";
	 
	}
	echo "</div>";
	}
		/*
	----------------------------------------------------
	Gestion du tournoi
	----------------------------------------------------
	*/

	if((isset($_POST['PasserTourSuivant']) || isset($_POST['DemarrerTournoi'])) && !isset($_POST['Synthese'])) {
		echo "<div class='nothidden' id='GestionTournoi'>";
	} else {
		echo "<div class='hidden' id='GestionTournoi'>";
	}
	echo "<h2> Gestion du Tournoi </h2>";

	$sql="SELECT TypeT FROM Tournoi WHERE IdTournoi=$IdTournoi";

	foreach ($dbh->query($sql) as $row){
		$champ=$row['TypeT'];	
	}

	if($champ=='Championnat'){
		echo "Les championnats avancent automatiquement";
	}
else{
	/*
	--------------------------------
	Démarrer le tournoi
	--------------------------------
	X-> Créer une liste de joueurs qui veulent s'inscrire dans un tournoi (une conséquente, et impaire pour le pire, 11 joueurs serait pas mal)
	X-> Passer le tournoi en phase "En cours"
	X-> Checker si l'arbre de tournoi contient 2^x cases, sinon en rajouter autant que nécessaire
	X-> Si nombre d'équipes impaires celle qui a le plus haut niveau joue solo
		-> Ensuite, sur les restantes:
		X-> on trie dans l'ordre, puis on recrée une liste en gouffre :
		X-> indices impairs / indices pairs
		X-> 1 - 3 - 5 - 7 - 8 - 6 - 4 - 2 
		X-> 1 vs 3 / 5 vs 7 / 8 vs 6 / 4 vs 2 ?
		X-> Puis on remplit tout le reste avec l'équipe nulle qui a un niveau de -1 
		X-> Si elle n'existe pas il faut la créer
		-> Enfin on crée les rencontres --- si dans une rencontre il y a l'équipe nulle, alors le vainqueur est l'autre équipe (si deux équipes nulles une gagne)
			-> et attention on mets des lieux et date à maintenant et ici
	-> Enfin on modifiera le passage au tour suivant pour qu'il prenne en compte les équipes nulles

	*/

	if(isset($_POST['DemarrerTournoi'])) {
		$IdTournoi = $_POST['DemarrerTournoi'];
		$dbh->exec("UPDATE Tournoi SET EtatTournoi = 'En cours' WHERE IdTournoi = $IdTournoi");
		
		//compter les feuilles de l'arbre
		$nbFeuilles = 0;
		foreach($dbh->query("SELECT Count(*) AS total FROM Arbre WHERE Tournoi = $IdTournoi") as $row) {
			$nbFeuilles = $row['total'];
		}
		//compter les inscrits dans le tournoi
		$nbInscrits = 0;
		foreach($dbh->query("SELECT COUNT(*) AS total FROM ListeEquipe WHERE IdTournoi = $IdTournoi AND AttenteInscription = 0") as $row) {
			$nbInscrits = $row['total'];
		}
		//calculer la hauteur qu'il faudrait:
		$hauteur = 0;
		while(pow(2, $hauteur+1) < $nbInscrits) $hauteur++;
		//ajouter le nombre de feuilles nécessaires	
		$FeuillesAjoutees = pow(2, $hauteur) - $nbFeuilles;
		for($i = 0; $i < $FeuillesAjoutees; $i++) {
			$dbh->exec("INSERT INTO Arbre (Tournoi, Hauteur) VALUES ($IdTournoi, $hauteur)");
		}
		//on récupère tous les id d'équipes à rentrer, on les mets en array, on garde en mémoire le meilleur
		$listeEquipe = array();
		$i = 0;
		$nivMax = -1;
		$indiceNivMax = -1;
		foreach($dbh->query("SELECT ListeEquipe.IdEquipe, Niveau FROM ListeEquipe, Equipe WHERE ListeEquipe.IdEquipe = Equipe.IdEquipe AND IdTournoi = $IdTournoi AND AttenteInscription = 0") as $row) {
			$listeEquipe['Id'][$i] = $row['IdEquipe'];
			if($row['Niveau'] != NULL) {
				$listeEquipe['Niveau'][$i] = $row['Niveau'];
			} else {
				$listeEquipe['Niveau'][$i] = 0;
			}
			if($listeEquipe['Niveau'][$i] > $nivMax) {
				$nivMax = $listeEquipe['Niveau'][$i];
				$indiceNivMax = $i;
			}
			$i++;
		}
		//on trie à la main parce que niveau est lié à id
		for($i = 0; $i < $nbInscrits; $i++) {
			$indiceMax = -1;
			$nivMax = -1;
			for($j = $i; $j < $nbInscrits; $j++) {
				if($listeEquipe['Niveau'][$j] > $nivMax) {
					$nivMax = $listeEquipe['Niveau'][$j];
					$indiceMax = $j;
				}
			}
			$saveNiv = $listeEquipe['Niveau'][$i];
			$saveId = $listeEquipe['Id'][$i];
			$listeEquipe['Niveau'][$i] = $listeEquipe['Niveau'][$indiceMax];
			$listeEquipe['Id'][$i] = $listeEquipe['Id'][$indiceMax];
			$listeEquipe['Niveau'][$indiceMax] = $saveNiv;
			$listeEquipe['Id'][$indiceMax] = $saveId;
		}
		//on réarrange, avec tous les pairs dans l'ordre en partant du début, les impairs en partant de la finale
		$min = 0;
		$max = $nbInscrits -1;
		$listeFinale = array();
		for($i = 0; $i < $nbInscrits; $i++) {
			if($i % 2 == 0) {
				$listeFinale['Niveau'][$min] = $listeEquipe['Niveau'][$i];
				$listeFinale['Id'][$min] = $listeEquipe['Id'][$i];
				$min++;
			} else {
				$listeFinale['Niveau'][$max] = $listeEquipe['Niveau'][$i];
				$listeFinale['Id'][$max] = $listeEquipe['Id'][$i];
				$max--;
			}
		}
		//on vérifie qu'il existe une équipe nulle, sinon on l'ajoute
		$IdEquipeNulle = -1;
		foreach($dbh->query("SELECT IdEquipe FROM Equipe WHERE Niveau = -1") as $row) {
			$IdEquipeNulle = $row['IdEquipe'];
		}
		if($IdEquipeNulle == -1) {
			$dbh->exec("INSERT INTO Equipe (Nom, Niveau, Images) VALUES ('EquipeNulle', -1, 'EquipeNulle.png')");
			foreach($dbh->query("SELECT LAST_INSERT_ID() AS lastid FROM Equipe") as $row) {
				$IdEquipeNulle = $row['lastid'];
			}
		}
		//si il manque du monde dans la liste on ajoute autant de fois l'équipe nulle que nécessaire
		//ici on veut répartir les équipes nulles plutôt que de les mettre toutes à la fin
		$nbEquipeAttendu = pow(2, $hauteur+1);
		$indiceNulle = 1; //on commence par mettre des équipes nulles en impair
		while($nbInscrits < $nbEquipeAttendu) {
			//on décale tout ce qui est après $indiceNulle de 1 cran
			for($i=$nbInscrits; $i>$indiceNulle; $i--) {
				$listeFinale['Niveau'][$i] = $listeFinale['Niveau'][$i-1];
				$listeFinale['Id'][$i] = $listeFinale['Id'][$i-1];
			}
			$listeFinale['Niveau'][$indiceNulle] = -1;
			$listeFinale['Id'][$indiceNulle] = $IdEquipeNulle;
			$indiceNulle += 2;
			//si on dépasse (moins de la moitié d'équipes inscrites), on reprend au début
			if($indiceNulle > $nbEquipeAttendu) $indiceNulle = 0;
			$nbInscrits++;
		}
		//on récupère la liste des feuilles
		$listeFeuilles = array();
		$i = 0;
		foreach($dbh->query("SELECT IdArbre FROM Arbre WHERE Tournoi = $IdTournoi") as $row) {
			$listeFeuilles[$i] = $row['IdArbre'];
			$i++;
		}
		$maxFeuilles = $i;
		//on crée les rencontres deux à deux, on rentre les nombres dans l'arbre
		for($i=0; $i<$nbInscrits; $i=$i+2) {
			//si niveau nul pour l'une des deux, on crée la rencontre et on update l'arbre
			if($listeFinale['Niveau'][$i] == -1 || $listeFinale['Niveau'][$i+1] == -1) {
				if($listeFinale['Niveau'][$i] > $listeFinale['Niveau'][$i+1]) {
					$vainqueur = $listeFinale['Id'][$i];
				} else {
					$vainqueur = $listeFinale['Id'][$i+1];
				}
				$dbh->query("INSERT INTO Rencontre (Equipe1, Equipe2, Tournoi, Vainqueur, EtatRencontre) VALUES (" . $listeFinale['Id'][$i] . ", " . $listeFinale['Id'][$i+1] . ", $IdTournoi, $vainqueur, 'Termine')");			
				$dbh->query("UPDATE Arbre SET Rencontre = LAST_INSERT_ID(), Equipe1 = " . $listeFinale['Id'][$i] . ", Equipe2 = " . $listeFinale['Id'][$i+1] . " WHERE IdArbre = " . $listeFeuilles[$i/2]);
			} else {
				$dbh->query("UPDATE Arbre SET Equipe1 = " . $listeFinale['Id'][$i] . ", Equipe2 = " . $listeFinale['Id'][$i+1] . " WHERE IdArbre = " . $listeFeuilles[$i/2]);
			}
		}
		
		
		/*
		for($i = 0; $i < $nbInscrits; $i++) {
			echo $listeFinale['Niveau'][$i] . " - " . $listeFinale['Id'][$i] . " / ";
		}
		echo "<br/>";
		*/	
	}

	/*
	--------------------------------
	Validation Passage tour suivant:
	-> Si hauteur = 0 : passer le tournoi en état terminé
	-> Sinon: créer les noeuds de l'arbre pour hauteur-1, puis y associer les bonnes équipes (les vainqueurs 2 à 2)
	--------------------------------------------------------------------
	ALGO DE PREPARATION DES IDARBRE DEUX A DEUX POUR LA NOUVELLE HAUTEUR
	$maListeFinale = array();      //pour stocker les valeurs, matrice
	$bool = 0;          //pour savoir si on est au second id ou pas
	$indice = 0;        //pour savoir où on en est dans le tableau
	$sauvegarde = 0;    //pour sauvegarder l'id précédent
	foreach($conn->query("maRequeteDIDArbre") as $row) {
	if($bool == 0) {    //premier du couple
	$sauvegarde = $row['IdArbre'];    //on sauvegarde l'id
	$bool = 1;
	} else {      //second du couple
	$maListeFinale[$indice] = array(); //chaque case du tableau contiendra deux cases: chaque id
	$maListeFinale[$indice][0] = $sauvegarde;
	$maListeFinale[$indice][1] = $row['IdArbre'];
	$bool = 0;
	$indice++;
	}
	}
	if($bool == 1) { //ça veut dire qu'il y a un id seul, si le nombre d'id est impair
	$maListeFinale[$indice] = array();
	$maListeFinale[$indice][0] = $sauvegarde;
	$maListeFinale[$indice][1] = -1;       //-1 pour dire qu'il n'y a pas d'id ici
	$indice++;
	}
	$longueurTab = $indice;  //pour savoir quelle taille fait ton tableau (inutile si tu foreach)
	--------------------------------------------------------------------
	*/

	if (isset($_POST['PasserTourSuivant'])) {
		$IdTournoi = $_POST['PasserTourSuivant'];

		$sql = "SELECT MIN(Hauteur) AS haut FROM Arbre WHERE Tournoi = $IdTournoi";
		foreach ($dbh->query($sql) as $row) {
			$hauteur = $row['haut'];
		}

		if ($hauteur == 0) { //passer le tournoi en terminé
			$sql = "UPDATE Tournoi SET EtatTournoi = 'Acheve' WHERE IdTournoi = $IdTournoi";
			$dbh->query($sql);
		} else { //créer les noeuds
			//sélectionner les idarbre et les vainqueurs pour les apparier
			$sql = "SELECT IdArbre, Vainqueur FROM Arbre, Rencontre WHERE Rencontre = IdRencontre AND Arbre.Tournoi = $IdTournoi AND Hauteur = (SELECT MIN(Hauteur) FROM Arbre WHERE Tournoi = $IdTournoi)";
			$listePaires = array();
			$bool = 0;
			$indice = 0;
			$saveId;
			$saveVainq;
			foreach ($dbh->query($sql) as $row) {
				if ($bool == 0) {
					$saveId = $row['IdArbre'];
					$saveVainq = $row['Vainqueur'];
					$bool = 1;
				} else {
					$listePaires[$indice] = array();
					$listePaires[$indice][0] = $saveId;
					$listePaires[$indice][1] = $saveVainq;
					$listePaires[$indice][2] = $row['IdArbre'];
					$listePaires[$indice][3] = $row['Vainqueur'];
					$bool = 0;
					$indice++;
				}
			}
			if ($bool == 1) {
				$listePaires[$indice] = array();
				$listePaires[$indice][0] = $saveId;
				$listePaires[$indice][1] = $saveVainq;
				$listePaires[$indice][2] = -1;
				$listePaires[$indice][3] = -1;
				$indice++;
			}
			//créer les noeuds dans idArbre et mettre à jour les noeuds dans idArbre (Fils doit correspondre)
			for ($i = 0; $i < $indice; $i++) {
				$sql = "INSERT INTO Arbre (Tournoi, Equipe1, Equipe2, Hauteur, PereGauche, PereDroit) VALUES ";
				$sql = $sql . "($IdTournoi, " . $listePaires[$i][1] . "," . $listePaires[$i][3] . "," . ($hauteur - 1) . "," . $listePaires[$i][0] . "," . $listePaires[$i][2] . ");";
				$dbh->query($sql);
				$dbh->query("UPDATE Arbre SET Fils = last_insert_id() WHERE IdArbre = " . $listePaires[$i][0]); 
				$dbh->query("UPDATE Arbre SET Fils = last_insert_id() WHERE IdArbre = " . $listePaires[$i][2]);
			}

			echo "<div class='alert alert-success'>Passage au tour suivant enregistré, veuillez patienter </div><br/>";

			//checker si des équipes sont à niveau nul (-1)
			$nbEquipesNulles = 0;
			$resultat = $dbh->query("SELECT IdArbre, Equipe1, Equipe2, E1.Niveau AS Niv1, E2.Niveau AS Niv2 FROM Arbre, Equipe AS E1, Equipe AS E2 WHERE Tournoi = $IdTournoi AND E1.IdEquipe = Equipe1 AND E2.IdEquipe = Equipe2 AND (E1.Niveau = -1 OR E2.Niveau = -1) AND Hauteur = (SELECT MIN(Hauteur) FROM Arbre WHERE Tournoi = $IdTournoi)");
			foreach($resultat as $row) {
				//si oui, créer les rencontres et mettre le plus fort en vainqueur
				if($row['Niv1'] > $row['Niv2']) {
					$vainqueur = $row['Equipe1'];
				} else {
					$vainqueur = $row['Equipe2'];
				}
				$dbh->exec("INSERT INTO Rencontre (Equipe1, Equipe2, Tournoi, Vainqueur, EtatRencontre) VALUES (" . $row['Equipe1'] . ", " . $row['Equipe2'] . ", $IdTournoi, $vainqueur, 'Termine')");
				$dbh->exec("UPDATE Arbre SET Rencontre = LAST_INSERT_ID() WHERE IdArbre = " . $row['IdArbre']);
			}

		}
		echo "<meta http-equiv='refresh' content='1;URL=GestionTournoi.php?redirection=GestionTournoi.php'/>";

	}

	/*
	-------------------------------

	Passer au tour suivant quand toutes les rencontres d'une hauteur d'un arbre sont terminées
	-> afficher les tournois du gestionnaire, avec la mention EtatTournoi
	-> afficher à quelle hauteur ils en sont
	-> afficher combien de rencontres il reste dans cette hauteur
	*/

	//à part pour les tournois en phase d'inscription
	$sql = "SELECT Tournoi.IdTournoi, Tournoi.Nom, COUNT(IdListeEquipe) AS Inscrits, NbEquipeMax, DatesDebut FROM Tournoi, ListeEquipe, Utilisateur WHERE ListeEquipe.IdTournoi = Tournoi.IdTournoi AND EtatTournoi = 'Phase inscription' AND AttenteInscription=0 AND LoginU = '$login' AND Tournoi.IdTournoi=$idTournoiC AND (RoleU = 'Administrateur' OR IdGestionnaire = IdUtilisateur) GROUP BY Tournoi.IdTournoi";
	foreach($dbh->query($sql) as $row) {
		echo "Tournoi " . $row['IdTournoi'] . " - " . $row['Nom'] . " (en phase d'inscription) : " . $row['Inscrits'] . " / " . $row['NbEquipeMax'] . " inscrits, démarre le " . $row['DatesDebut'];
		//impossible de démarrer le tournoi si 0 inscrits
		if($row['Inscrits'] != 0) {
			echo "<form method=post action=GestionTournoi.php><input type='hidden' name='DemarrerTournoi' value=" . $row['IdTournoi'] . "><input type='submit' class='click' value='Démarrer le tournoi'></form>";
		}
		echo "<br/>";	
	}
	
	if(isset($_POST['DemarrerTournoi'])){
		echo "<meta http-equiv='refresh' content='1;URL=GestionTournoi.php?redirection=GestionTournoi.php'/>";
	}
	
	$sql = "SELECT IdTournoi, Tournoi.Nom, Tournoi.EtatTournoi, MIN(Hauteur) AS hauteur, COUNT(Vainqueur) AS nbVainqueurs FROM Tournoi, Arbre, Rencontre, Utilisateur WHERE Arbre.Tournoi = IdTournoi AND (Arbre.Rencontre = IdRencontre OR Arbre.Rencontre IS NULL) AND Hauteur = (SELECT MIN(Hauteur) FROM Arbre WHERE Tournoi = IdTournoi) AND (IdGestionnaire = IdUtilisateur OR RoleU = 'Administrateur') AND LoginU = '$login' AND Tournoi.IdTournoi=$idTournoiC GROUP BY IdTournoi";

	foreach ($dbh->query($sql) as $row) {
		if($row['EtatTournoi'] != 'Phase inscription') {
			echo "Tournoi " . $row['IdTournoi'] . " - " . $row['Nom'] . " : actuellement ";
			if ($row['EtatTournoi'] == "Acheve") {
				echo "terminé<br/>";
			} else {
				echo "en " . hauteurEnMots($row['hauteur']);
				$nbMatchRestants = pow(2, $row['hauteur']) - $row['nbVainqueurs'];
				if ($nbMatchRestants < 0) {
					//les rencontres ne sont pas remplies, ça attrape la totalité de la base de données
					echo " --------- [Rencontres à définir]";			
				} else {		
					echo " --------- reste " . $nbMatchRestants . " match";
				}

				if ($nbMatchRestants == 0) {
					echo "<form method=post action=GestionTournoi.php><input type='hidden' name='PasserTourSuivant' value=" . $row['IdTournoi']	 . "><input type='submit' class='click' value='Passer au tour suivant'></form>";
				
				}
				echo "<br/>";
			}
		}
	}
}
	echo "</div></div>";

}

?>
<!---------------------
Gestion des onglets
---------------------->
<script>
	function onglet(numOnglet) {
		for (let i = 1; i < 6; i++) {
			if (i == numOnglet) {
				document.getElementById('onglet' + i.toString()).className = 'onglet surligne';
			} else {
				document.getElementById('onglet' + i.toString()).className = 'onglet';
			}
		}
		document.getElementById('GestionSynthese').className = 'hidden';
		document.getElementById('GestionValidation').className = 'hidden';
		document.getElementById('GestionRencontre').className = 'hidden';
		document.getElementById('GestionScore').className = 'hidden';
		document.getElementById('GestionTournoi').className = 'hidden';
		switch (numOnglet) {
			case 1:
				document.getElementById('GestionSynthese').className = 'nothidden';
				break;
			case 2:
				document.getElementById('GestionValidation').className = 'nothidden';
				break;
			case 3:
				document.getElementById('GestionRencontre').className = 'nothidden';
				break;
			case 4:
				document.getElementById('GestionScore').className = 'nothidden';
				break;
			case 5:
				document.getElementById('GestionTournoi').className = 'nothidden';
				break;
		}
	}
</script>


<?php

/*
-------------------------------
Appel au formulaire de synthèse
-------------------------------
*/
include("GestionTournoiSynthese.php");

echo "</div>";

include("../CadreStatique/skel2.php");
?>

</html>
