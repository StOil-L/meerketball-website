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
$_SESSION['historique']['redirection'] = 3;
$titreOnglet = "Connexion";
$linkPage = "../Formulaires/LoginUtilisateur.php";
$titrePage = "Confirmez votre identité";
?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");
?>

<form action='' method='post'>
	<label for='log'> Identifiant : </label>
	<input type='text' id='log' size='20' name='login'><br>
	<label for='pwd'> Mot de passe : </label>
	<input type='password' id='pwd' size='20' name='motdepasse'><br>
	<input class='click' type='submit' value='Valider' name='valid'>
</form>
<br>

<?php
if ((isset($_POST['valid'])) and ((!empty($_POST['login'])) && (!empty($_POST['motdepasse'])))) {
	//recup le mdp hach de la database
	$req = $dbh->prepare("SELECT MotDePasse FROM Utilisateur WHERE LoginU = '" . $_POST['login'] . "'");
	$req->execute();
	$res = $req->fetchALL(PDO::FETCH_OBJ);
	$mdpdb;
	foreach ($res as $mmm) {
		$mdpdb = $mmm->MotDePasse;
	}
	//compare les 2
	if (isset($mdpdb)) {
		$pass_verif = password_verify($_POST['motdepasse'], $mdpdb);
		// echo $mdpHach;
		// echo "<br>";
		// echo $mdpdb;
		if (!$pass_verif) {
			echo "<div class='alert alert-danger'>
				Identifiant inconnu ou Mot de passe incorrect..<br>
				</div>
				";
		} else {
			$req3 = $dbh->prepare("SELECT IdUtilisateur, Nom, Prenom, RoleU, OptionDalt FROM Utilisateur WHERE LoginU = '" . $_POST['login'] . "'");
			$req3->execute();
			$res3 = $req3->fetch(PDO::FETCH_ASSOC);
			$_SESSION['historique']['login'] = $_POST['login'];
			$_SESSION['historique']['idLogin'] = $res3['IdUtilisateur'];
			$_SESSION['historique']['RoleU'] = $res3['RoleU'];
			$_SESSION['historique']['dalt'] = $res3['OptionDalt'];
?>
			<script>
				if (sessionStorage.getItem("pagePrec") == null) {
					window.location = "../CadreStatique/index1.php"
				} else {
					window.location = (sessionStorage.getItem("pagePrec"));
				}
			</script>
<?php
		}
	} else {
		echo "<div class='alert alert-danger'>
		Identifiant inconnu ou Mot de passe incorrect..<br>
		</div>
		";
	}
}
include("../CadreStatique/skel2.php");
?>

</html>