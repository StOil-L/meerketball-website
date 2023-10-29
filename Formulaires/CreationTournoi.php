<?php
session_start();
include("connexion.php");
date_default_timezone_set('Europe/Paris');

try {
	$dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
	echo $e->getMessage();
	die("Connexion impossible.");
}

$titreOnglet = "Création";
$linkPage = "../Formulaires/CreationTournoi.php";
$titrePage = "Création de tournoi";

?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");
?>

<h1>Création de tournoi</h1>
<span id='consigne'>Les champs renseignés avec * sont obligatoires.</span>
<br>
<br>
<?php 
$admincheck = false;
if(isset($_SESSION['historique']['RoleU'])){
	if($_SESSION['historique']['RoleU'] == "Administrateur"){
		$admincheck = true;
	}
}

$except = 0;
if(isset($_POST['creer']) && !empty($_FILES["imageTournoiNAME"]['name'])) {
	$fichier_dest = "../ImagesUtilisateur/Tournois/".basename($_FILES["imageTournoiNAME"]["name"]);
	$envoi = 1;
	$verif = getimagesize($_FILES["imageTournoiNAME"]["tmp_name"]);
	if($verif == false) {
		$except = 1;
		$envoi = 0;
	}
	if (file_exists($fichier_dest)){
		$except = 2;
		$envoi = 0;
	}
	if ($envoi == 1){
		if (move_uploaded_file($_FILES["imageTournoiNAME"]["tmp_name"], $fichier_dest)) {
			echo "<div class='done'>
			Fichier envoyé.
			</div>";
		}
		else{
			echo "<div class='alert alert-danger'>
			L'envoi de l'image a échoué (raison inconnue).
			</div>";
		}
	}
}

if(isset($_POST['creer'])){
	$_SESSION['historique']['gestTournoiNAME'] = $_POST['gestTournoiNAME'];
	$gest = $_SESSION['historique']['gestTournoiNAME'];
	$_SESSION['historique']['nomTournoiNAME'] = $_POST['nomTournoiNAME'];
	$nom = $_SESSION['historique']['nomTournoiNAME'];
	if(empty($nom)){
		$nom = "Tournoi sans nom";
	}
	$_SESSION['historique']['dateTournoiNAME'] = $_POST['dateTournoiNAME'];
	$date = $_SESSION['historique']['dateTournoiNAME'];
	$_SESSION['historique']['dureeTournoiNAME'] = $_POST['dureeTournoiNAME'];
	$duree = $_SESSION['historique']['dureeTournoiNAME'];
	$_SESSION['historique']['lieuTournoiNAME'] = $_POST['lieuTournoiNAME'];
	$lieu = $_SESSION['historique']['lieuTournoiNAME'];
	if(empty($lieu)){
		$lieu = "Lieu inconnu";
	}
	$_SESSION['historique']['nbEquipesMaxTournoiNAME'] = $_POST['nbEquipesMaxTournoiNAME'];
	$nbequipes = $_SESSION['historique']['nbEquipesMaxTournoiNAME'];
	$type = "";
	if(isset($_POST['typeTournoiNAME'])){
		$_SESSION['historique']['typeTournoiNAME'] = $_POST['typeTournoiNAME'];
		$type = $_SESSION['historique']['typeTournoiNAME'];
		if($type == "Coupe"){
			$type = "Tournoi à élimination directe";	
		}
		else if($type == "PlusieursTours"){
			$type = 'Tournoi aller-retour';
		}
	}
	$categorie = "";
	if(isset($_POST['categorieTournoiNAME'])){
		$_SESSION['historique']['categorieTournoiNAME'] = $_POST['categorieTournoiNAME'];
		$categorie = $_SESSION['historique']['categorieTournoiNAME'];
	}
	$_SESSION['historique']['imageTournoiNAME'] = $_FILES["imageTournoiNAME"]['name'];
	$image = $_SESSION['historique']['imageTournoiNAME'];
	if(empty($image)){
		$image = "defautTournoi.png";
	}
}
?>
<form action='' method='post' enctype="multipart/form-data">
<label for='gestTournoiID'> Gestionnaire* : </label> 
<select id='gestTournoiID' name='gestTournoiNAME' required >
<?php

$gesthist = "";
if(isset($_SESSION['historique']['gestTournoiNAME'])){
	$req0 = $dbh->prepare("SELECT LoginU, Nom, Prenom FROM Utilisateur WHERE IdUtilisateur=".$_SESSION['historique']['gestTournoiNAME']."");
	$req0->execute();
	$res0 = $req0->fetch(PDO::FETCH_ASSOC);
	$gesthist = $_SESSION['historique']['gestTournoiNAME'];
}
?>
	<option selected='selected' value='<?= $gesthist; ?>' <?= !empty($gesthist)? : "disabled";?>><?= !empty($gesthist)? $res0['LoginU']." : ".$res0['Prenom']." ".$res0['Nom'] : "Sélectionner";?></option>
<?php
$req = $dbh->prepare("SELECT IdUtilisateur, LoginU, Nom, Prenom FROM Utilisateur");
$req->execute();
$res = $req->fetchAll(PDO::FETCH_ASSOC);
foreach($res as $row){
	if($row['IdUtilisateur'] != $gesthist){
		echo "
		<option value='".$row['IdUtilisateur']."'>".$row['LoginU']." : ".$row['Prenom']." ".$row['Nom']."</option>
		";
	}
}
?>
</select> Attention, le gestionnaire choisi pourra modifier l'ensemble des paramètres du tournoi.<br/>

<label for='nomTournoiID'> Nom du tournoi : </label> 
<input type='text' id='nomTournoiID' maxlength='18' name='nomTournoiNAME' pattern='[A-Za-z0-9À-ÿ.,+\-/\*(): ]*' value='<?= isset($_SESSION['historique']['nomTournoiNAME'])? $_SESSION['historique']['nomTournoiNAME'] : ""; ?>'>  <br>
<label for='dateTournoiID'> Date de début* : </label>
<input type='date' id='dateTournoiID' name='dateTournoiNAME' value='<?= isset($_SESSION['historique']['dateTournoiNAME'])? $_SESSION['historique']['dateTournoiNAME'] : date("Y-m-d"); ?>' required> <br>
<label for='dureeTournoiID'> Durée (jours)* : </label>
<input type='number' id='dureeTournoiID' name='dureeTournoiNAME' min='1' value='<?= isset($_SESSION['historique']['dureeTournoiNAME'])? $_SESSION['historique']['dureeTournoiNAME'] : 1; ?>' required> <br>
<label for='lieuTournoiID'> Lieu : </label>
<input type='text' id='lieuTournoiID' size='50' name='lieuTournoiNAME' pattern='[A-Za-z0-9À-ÿ.,+\-/\*(): ]*' value='<?= isset($_SESSION['historique']['lieuTournoiNAME'])? $_SESSION['historique']['lieuTournoiNAME'] : ""; ?>'> <br>
<label for='nbEquipesMaxTournoiID'> Nombre maximum d'équipes* : </label>
<input type='number' id='nbEquipesMaxTournoiID' name='nbEquipesMaxTournoiNAME' min='2' max='128' step='2' value='<?= isset($_SESSION['historique']['nbEquipesMaxTournoiNAME'])? $_SESSION['historique']['nbEquipesMaxTournoiNAME'] : 2; ?>' required> <br>
<label for='typeTournoiID'> Type* : </label>
<select id='typeTournoiID' name='typeTournoiNAME' required>
	<option selected='selected' <?= isset($_SESSION['historique']['typeTournoiNAME'])? : "disabled"; ?>> <?= isset($_SESSION['historique']['typeTournoiNAME'])? $_SESSION['historique']['typeTournoiNAME'] : "Sélectionner"; ?></option>
	<option value='Coupe'>Tournoi à élimination directe</option>
	<option value='Championnat'>Championnat</option>
	<option value='Poule'>Poule [indisponible]</option>
	<option value='PlusieursTours'>Tournoi aller-retour [indisponible]</option>
</select><br>
<label for='categorieTournoiID'>Catégorie : </label>
<select id='categorieTournoiID' name='categorieTournoiNAME' required>
    <option selected='selected' <?= isset($_SESSION['historique']['categorieTournoiNAME'])? : "disabled"; ?>> <?= isset($_SESSION['historique']['categorieTournoiNAME'])? $_SESSION['historique']['categorieTournoiNAME'] : "Sélectionner"; ?></option>
    <option value='Poussin'>Poussin</option>
    <option value='Benjamin'>Benjamin</option>
    <option value='Junior'>Junior</option>
    <option value='Senior'>Senior</option>
    <option value='Handicap'>Handicap</option>
    <option value='FeminineSenior'>Féminine Senior</option>
</select><br/>
<label for='imageTournoiID'> Image du tournoi: </label>
<input type='file' id='imageTournoiID' name='imageTournoiNAME' accept='image/png, image/jpeg'><br>
<?php 
if($admincheck){
	echo "<input class='click' type='submit' value='Créer le tournoi' name='creer'>";
}
?>
</form>
<br>
<?php
function calculHauteur(int $n){
	$i = 0;
	while(pow(2, $i+1) < $n){
		$i++;
	}
	return $i;
}

if(isset($_POST['creer'])){
	$ready = 0;
	if($duree >= 1){
		$ready++;
	}
	else{
		echo "<div class='alert alert-danger'>
		Erreur: durée invalide.
		</div>";
	}
	if(($nbequipes >= 2) && ($nbequipes % 2 == 0)){
		$ready++;
	}
	else{
		echo "<div class='alert alert-danger'>
		Erreur: nombre d'équipes invalide.
		</div>";
	}
	if(!empty($type)){
		$ready++;
	}
	else{
		echo "<div class='alert alert-danger'>
		Erreur: type de tournoi obligatoire.
		</div>";
	}
	if(!empty($categorie)){
		$ready++;
	}
	else{
		echo "<div class='alert alert-danger'>
		Erreur: catégorie de tournoi obligatoire.
		</div>";
	}
	
	switch($except){
		case 1:
			echo "<div class='alert alert-danger'>
			Erreur: format incorrect (attendus: JPEG/JPG/PNG).
			L'envoi de l'image a échoué.
			</div>";
		case 2:
			echo "<div class='alert alert-danger'>
			Erreur: le fichier existe déjà.
			L'envoi de l'image a échoué.
			</div>";
	}
	if($type=='Tournoi à élimination directe'){
	$type='Coupe';
	}
	else if($type=='Tournoi aller-retour'){
		$type='PlusieursTours';
	}
	if($ready == 4){
        $existeDeja='';
		$sql="SELECT IdTournoi FROM Tournoi WHERE Nom='$nom' AND TypeT='$type' AND Categorie='$categorie' AND EtatTournoi<>'Acheve'";
		foreach($dbh->query($sql) as $row){
			$existeDeja=$row['IdTournoi'];
		}
		if($existeDeja==''){
		$req2 = $dbh->prepare("INSERT INTO Tournoi 
		(IdGestionnaire, Nom, EtatTournoi, DatesDebut, Duree, Lieu, NbEquipeMax, TypeT, Images, Categorie)
		VALUES(".$gest.",'".$nom."','Phase inscription','".$date."',".$duree.",'".$lieu."',".$nbequipes.",'".$type."','".$image."','".$categorie."')");
		$req2->execute();
		$idtournoi = $dbh->lastInsertId();
		echo "<div class='alert alert-success'>
		Tournoi créé: ".$nom.".<br>
		";
		
		$req3 = $dbh->prepare("SELECT RoleU FROM Utilisateur WHERE IdUtilisateur=".$gest."");
		$req3->execute();
		$res3 = $req3->fetch(PDO::FETCH_ASSOC);
		if(($res3['RoleU'] != 'Administrateur') || ($res3['RoleU'] != 'Gestionnaire')){
			$req4 = $dbh->prepare("UPDATE Utilisateur SET RoleU='Gestionnaire' WHERE IdUtilisateur=".$gest."");
			$req4->execute();
		}
		if($_SESSION['historique']['typeTournoiNAME']=='Coupe'){
			$hauteur = calculHauteur($nbequipes);
			for($i = 1; $i <= $nbequipes; $i += 2){
				$req5 = $dbh->prepare("INSERT INTO Arbre (Tournoi, Hauteur) VALUES(".$idtournoi.", ".$hauteur.")");
				$req5->execute();
			}
		}
		else if($_SESSION['historique']['typeTournoiNAME']=='Championnat'){
			$k=	$_SESSION['historique']['nbEquipesMaxTournoiNAME'];
			for($i=0;$i<$k/2*(2*$k-2);$i+=1){
				$dbh->exec("INSERT INTO Rencontre(Tournoi) VALUES('$idtournoi')");
				$sql="SELECT IdRencontre FROM Rencontre WHERE IdRencontre>=(SELECT max(IdRencontre) FROM Rencontre) AND Tournoi='$idtournoi'";
				foreach($dbh->query($sql) as $row){
			$dbh->exec("INSERT INTO Championnat(Tournoi,Rencontre) VALUES( '$idtournoi',$row[IdRencontre])");
			}
		}
	}

		$req6 = $dbh->prepare("SELECT LoginU, Nom, Prenom FROM Utilisateur WHERE IdUtilisateur=".$gest."");
		$req6->execute();
		$res6 = $req6->fetch(PDO::FETCH_ASSOC);
		echo "
		Son gestionnaire est ".$res6['Prenom']." ".$res6['Nom']." (".$res6['LoginU'].").
		</div>";

	}
	else echo "<div class='alert alert-danger'> Le tournoi existe déjà.";

}

}


include("../CadreStatique/skel2.php");
?>

</html>
<title>Formulaire 1 - </title>
</head>

<body>
