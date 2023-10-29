<?php
session_start();
include("connexion.php");
date_default_timezone_set('Europe/Paris');

try {
	$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
	echo $e->getMessage();
	die("connexion Impossible !");
}

$titreOnglet = "Affichage Tournoi";
$linkPage = "../Formulaires/AffichageTournoi.php";
$titrePage = "Affichage Tournoi";

?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");
?>

<style>

.tooltip2 {
	position: relative;
	display: inline-block;
	width: 100%;
	height: 100%;
	background-color: transparent;
}
  
.tooltip2 .tooltiptext2 {
	visibility: hidden;
	width: 120px;
	background-color: #555;
	color: #fff;
	text-align: center;
	padding: 5px 0;
	border-radius: 6px;
	  
	/* Position du texte */
	position: absolute;
	z-index: 99;
	top: 105%;
	left: 50%;
	margin-left: -60px;
	  
	/* tooltip moins abrupt */
	opacity: 0;
	transition: opacity 0.5s;
}
 
.tooltip2:hover .tooltiptext2 {
	visibility: visible;
	opacity: 1;
}
</style>


<h2>Meerkat - Affichage Tournoi</h2>


<div id="wrapper2" class="wrapper2">
	<?php
	if (isset($_POST['afficherTournoi'])) {
		$IdTournoi = $_POST['afficherTournoi'];
		//trouver hauteur max
		$sql = "SELECT MAX(Hauteur) AS max FROM Arbre WHERE Tournoi = $IdTournoi";
		foreach ($conn->query($sql) as $row) {
			$Hauteur = $row['max'];
		}
		$bordureGauche = 20;
		if ($Hauteur != null) {
			//echo $Hauteur;
			//matrice pour enregistrer les enfants de chaque rencontre
			$matrice = array();
			//cette variable devient 1 lorsqu'il n'y a plus rien à afficher
			$plusRienAAfficher = 0;
			//lancer une boucle pour afficher les div de chaque rencontre
			for ($hauteur = $Hauteur; $hauteur >= 0; $hauteur--) {
				if ($plusRienAAfficher == 0) {
					$matrice[$hauteur] = array();

					if ($hauteur == $Hauteur) {
						//vérifier s'il y a des entrées dans l'arbre
						$entrees = 0;
						foreach ($conn->query("SELECT COUNT(*) AS total FROM Arbre WHERE Tournoi=$IdTournoi AND Hauteur=$hauteur") as $row) {
							$entrees = $row['total'];
						}
						if ($entrees != 0) {
							$i = 1;
							//requeter tous les noeuds à la hauteur max
							$sql = "SELECT Fils, Rencontre FROM Arbre WHERE Tournoi = $IdTournoi AND Hauteur = $hauteur ORDER BY Fils";
							$resultats = $conn->query($sql);
							foreach ($resultats as $row) {
								//on enregistre le fils dans la matrice
								$matrice[$hauteur][$i] = $row['Fils'];
								//echo "$i : " . $matrice[$hauteur][$i] . "<br>";
								$rencontre = $row['Rencontre'];
								if ($rencontre != null) {
									//on demande les noms de l'équipe1, équipe2, vainqueur, scores
									$sql = "SELECT E1.Nom AS e1nom, E2.Nom AS e2nom, V.Nom AS vnom, ScoreEquipe1, ScoreEquipe2, DateRencontre, Lieu, Duree, TempsAdditionnel, EtatRencontre FROM Rencontre, Equipe AS E1, Equipe AS E2, Equipe AS V WHERE IdRencontre = $rencontre AND E1.IdEquipe = Equipe1 AND E2.IdEquipe = Equipe2 AND V.IdEquipe = Vainqueur";
									foreach ($conn->query($sql) as $elem) {
										$equipe1 = $elem['e1nom'];
										$equipe2 = $elem['e2nom'];
										$vainqueur = $elem['vnom'];
										$score1 = $elem['ScoreEquipe1'];
										$score2 = $elem['ScoreEquipe2'];
										$dateRencontre = $elem['DateRencontre'];
										$lieu = $elem['Lieu'];
										$duree = $elem['Duree'];
										$tempsAdditionnel = $elem['TempsAdditionnel'];
										$etatRencontre = $elem['EtatRencontre'];
									}
									$equipe1 = strlen($equipe1) > 20 ? substr($equipe1, 0, 20) : $equipe1;
									$equipe2 = strlen($equipe2) > 20 ? substr($equipe2, 0, 20) : $equipe2;
									$vainqueur = strlen($vainqueur) > 20 ? substr($vainqueur, 0, 20) : $vainqueur;

									//on affiche la rencontre
									$left = $i * 160 - 150 + $bordureGauche;
									$top = $hauteur * 120 + 150;
									$div = "<div style=\"border: 1px solid black; position: absolute; left: " . $left . "px; top: " . $top  . "px; margin: 0; padding: 0; text-align: center; width: 150px; height: 70px; font-size: 80%\"><div class='tooltip2'><p style='margin: 0; padding: 0;";
									if ($equipe1 == $vainqueur) {
										$div = $div . "color: red;";
									}
									$div = $div . "'>$equipe1</p><p style='padding: 0; margin: 0'>$score1 - $score2</p><p style='margin: 0; padding: 0;";
									if ($equipe2 == $vainqueur) {
										$div = $div . "color: red;";
									}
									$div = $div . "'>$equipe2</p>";
									//tooltip
									$div = $div . "<div class='tooltiptext2'>$equipe1 contre $equipe2<br/>$etatRencontre<br/>$dateRencontre<br/>$lieu<br/>$duree min (+ $tempsAdditionnel min)</div></div></div>";
									echo $div;
								}
								$i++;
							}
						}
					} else {
						//on vérifie s'il y a des entrées à cette hauteur
						$entrees = 0;
						foreach ($conn->query("SELECT COUNT(*) AS total FROM Arbre WHERE Tournoi=$IdTournoi AND Hauteur=$hauteur") as $row) {
							$entrees = $row['total'];
						}
						if ($entrees != 0) {
							//on tire les infos du rang précédent de la matrice
							for ($i = 1; $i <= pow(2, $hauteur); $i++) {
								$idArbre = $matrice[$hauteur + 1][2 * $i];
								//echo $idArbre . " / hauteur: $hauteur / pow: " . pow(2, $hauteur) . "<br>";
								//on requete la rencontre correspondante, on enregistre le fils dans la matrice
								$sql = "SELECT Fils, Rencontre FROM Arbre WHERE IdArbre = $idArbre";
								foreach ($conn->query($sql) as $row) {
									$matrice[$hauteur][$i] = $row['Fils'];
									$rencontre = $row['Rencontre'];
								}
								if ($rencontre != null) {
									//on demande les noms de l'équipe1, équipe2, vainqueur, scores
									$sql = "SELECT E1.Nom AS e1nom, E2.Nom AS e2nom, ScoreEquipe1, ScoreEquipe2, DateRencontre, Lieu, Duree, TempsAdditionnel, EtatRencontre FROM Rencontre, Equipe AS E1, Equipe AS E2 WHERE IdRencontre = $rencontre AND E1.IdEquipe = Equipe1 AND E2.IdEquipe = Equipe2";
									foreach ($conn->query($sql) as $elem) {
										$equipe1 = $elem['e1nom'];
										$equipe2 = $elem['e2nom'];
										$score1 = $elem['ScoreEquipe1'];
										$score2 = $elem['ScoreEquipe2'];
										$dateRencontre = $elem['DateRencontre'];
										$lieu = $elem['Lieu'];
										$duree = $elem['Duree'];
										$tempsAdditionnel = $elem['TempsAdditionnel'];
										$etatRencontre = $elem['EtatRencontre'];
									}
									$vainqueur = -1;
									$sql = "SELECT V.Nom AS vnom FROM Rencontre, Equipe AS V WHERE IdRencontre = $rencontre AND V.IdEquipe = Vainqueur";
									foreach ($conn->query($sql) as $elem) {
										$vainqueur = $elem['vnom'];
									}
									$equipe1 = strlen($equipe1) > 20 ? substr($equipe1, 0, 20) : $equipe1;
									$equipe2 = strlen($equipe2) > 20 ? substr($equipe2, 0, 20) : $equipe2;
									$vainqueur = strlen($vainqueur) > 20 ? substr($vainqueur, 0, 20) : $vainqueur;

									//on affiche la rencontre

									//ATTENTION CECI EST UNE FORMULE MAGIQUE
									//JE L'AI ECRITE MAIS NE PEUT PLUS LA COMPRENDRE
									//J'AI DEUX PAGES DE BROUILLON HYEROGLYPHIQUE ILLISIBLES
									//ON NE TOUCHE SURTOUT PAS
									//100 -> taille d'une div
									//10 -> séparation entre les deux
									$tailleDiv = 150;
									$separationDiv = 10;
									$total = $tailleDiv + $separationDiv;

									$left = ($i - 1) * ($total * pow(2, ($Hauteur - $hauteur))) + $total * pow(2, ($Hauteur - $hauteur) - 1) - $total / 2 + 10 + $bordureGauche;
									$top = $hauteur * 120 + 150;
									$div = "<div style=\"border: 1px solid black; position: absolute; left: " . $left . "px; top: " . $top  . "px; margin: 0; padding: 0; text-align: center; width: 150px; height: 70px; font-size: 80%\"><div class='tooltip2'><p style='margin: 0; padding: 0;";
									if ($equipe1 == $vainqueur) {
										$div = $div . "color: red;";
									}
									$div = $div . "'>$equipe1</p><p style='padding: 0; margin: 0'>$score1 - $score2</p><p style='margin: 0; padding: 0;";
									if ($equipe2 == $vainqueur) {
										$div = $div . "color: red;";
									}
									$div = $div . "'>$equipe2</p>";
									//tooltip				
									$div = $div . "<div class='tooltiptext2'>$equipe1 contre $equipe2<br/>$etatRencontre<br/>$dateRencontre<br/>$lieu<br/>$duree min (+ $tempsAdditionnel min)</div></div></div>";
									echo $div;

									//on affiche les traits de jonction
									$width = pow(2, ($Hauteur - $hauteur)) * 80;
									$top = $hauteur * 120 + 245;
									$left = ($i - 1) * ($total * pow(2, ($Hauteur - $hauteur))) + $total * pow(2, ($Hauteur - $hauteur) - 2) + 5 + $bordureGauche;
									$div = "<div style='border-top: 1px solid green; border-left: 1px solid green; border-right: 1px solid green; position: absolute; left: " . $left . "px; top: " . $top . "px; margin: 0; padding: 0; width: " . $width . "px; height: 24px'></div>";
									echo $div;

									$top = $hauteur * 120 + 221;
									$left = ($i - 1) * ($total * pow(2, ($Hauteur - $hauteur))) + $total * pow(2, ($Hauteur - $hauteur) - 1) + 5 + + $bordureGauche;
									$div = "<div style='border-left: 1px solid green; position: absolute; left: " . $left . "px; top: " . $top . "px; margin: 0; padding: 0; width: 2px; height: 24px'></div>";
									echo $div;
								}
							}
						} else {
							$plusRienAAfficher = 1;
						}
					}
				}
			}
		} else {
			echo "Aucune entrées pour le tournoi (id $IdTournoi)";
		}
	}
	?>
</div>
<?php
include("../CadreStatique/skel2.php");
?>

</html>