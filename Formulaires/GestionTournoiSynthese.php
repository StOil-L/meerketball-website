<?php

if(isset($_POST['Synthese']) || !(isset($_POST['equipesAValider']) || isset($_POST['RencontreValidation']) || isset($_POST['RencontresIdTournoi']) || isset($_POST['score1'], $_POST['score2']) || isset($_POST['PasserTourSuivant']) || isset($_POST['DemarrerTournoi']))) {
	echo "<div class='nothidden' id='GestionSynthese'>";
} else {
	echo "<div class='hidden' id='GestionSynthese'>";
}

/*
----------------
Menu déroulant de choix de tournoi
----------------
*/


/*
-----------------
Récupérer le tournoi à afficher
-----------------
*/


if(isset($_SESSION['historique']['RencontresIdTournoi'])){
	$IdTournoi = $_SESSION['historique']['RencontresIdTournoi'];


/*
------------------
Editer le tournoi : édition
------------------
*/	
if(isset($_POST['EditerTournoi'])) {
	$Nom = $_POST['Nom'];
	$DatesDebut = $_POST['DatesDebut'];
	$Lieu = $_POST['Lieu'];
	$NbEquipeMax = $_POST['NbEquipeMax'];	
	$dbh->exec("UPDATE Tournoi SET Nom='$Nom', DatesDebut='$DatesDebut', Lieu='$Lieu', NbEquipeMax=$NbEquipeMax WHERE IdTournoi=$IdTournoi");	
}

if($IdTournoi != -1) {
	/*
	-----------------
	Vérifier si le tournoi est encore en phase d'inscription
	-----------------
	*/
	$EtatTournoi = -1;
	$TypeTournoi = -1;
	foreach($dbh->query("SELECT Utilisateur.Nom AS unom, Utilisateur.Prenom, EtatTournoi, Tournoi.Nom AS tnom, IdGestionnaire, DatesDebut, Duree, Lieu, NbEquipeMax, TypeT, Tournoi.Images FROM Tournoi, Utilisateur WHERE IdTournoi = $IdTournoi AND IdUtilisateur = IdGestionnaire") as $row) {
		$EtatTournoi = $row['EtatTournoi'];
		$NomTournoi = $row['tnom'];
		$GestionnaireTournoi = $row['IdGestionnaire'];
		$NomGestionnaireTournoi = $row['unom'];
		$PrenomGestionnaireTournoi = $row['Prenom'];
		$DatesDebutTournoi = $row['DatesDebut'];
		$DureeTournoi = $row['Duree'];
		$LieuTournoi = $row['Lieu'];
		$NbEquipeMaxTournoi = $row['NbEquipeMax'];
		$TypeTournoi = $row['TypeT'];
		$ImagesTournoi = $row['Images'];
	}
	if($TypeTournoi != 'Coupe') {
		echo "Synthèse indisponible pour les tournois qui ne sont pas à élimination directe";
	} else {
		/*
		------------------
		Editer le tournoi : affichage
		------------------
		*/	
		echo "<input type='submit' class='click-ligne' value='$NomTournoi' onClick='ouvrirChange(\"-1\")'><br/>";
		echo "<div style='position: absolute; top: 0%; right: 2%;'>Cliquez sur les cases pour modifier les rencontres</div>";
		echo "<div style='border: 1px solid black; position: absolute; top: 5%; left: 5%; width: 90%; height: 90%; margin: 0; padding: 0; z-index: 4000000; background-color: lightgrey; display: none;' id='CaseChange-1'>";
		//bouton de fermeture en haut à droite
		echo "<div style='position: absolute; top: 0; right: 0; width: 30px; height: 40px; font-size: 38px; line-height: 38px; padding: 0; font-weight: bold; color: red;' onClick='fermerChange(\"-1\")'>X</div>";
		//titre
		echo "<div style='text-align: center'>";
		echo "<h4>Editer les informations du tournoi</h4><h5>$NomTournoi : </h5></div>";
		echo "<div class='alert alert-success'>";
		echo "<form method=post>";
		echo "Nom : <input type='text' name='Nom' value='$NomTournoi'><br/>";
		echo "Gestionnaire : $PrenomGestionnaireTournoi $NomGestionnaireTournoi<br/>";
		//changement de gestionnaire seulement si l'utilisateur est admin
		echo "Date de début : <input type='date' name='DatesDebut' value=$DatesDebutTournoi><br/>";
		echo "Lieu : <input type='text' name='Lieu' value='$LieuTournoi'><br/>";
		echo "Nombre d'équipes maximum : <input type='number' name='NbEquipeMax' value=$NbEquipeMaxTournoi min='2' max='128' step='2'><br/>";
		echo "Type : $TypeTournoi<br/>";
		echo "Images : $ImagesTournoi<br/>";
		echo "<input type='hidden' name='Synthese' value='vrai'><input type='hidden' name='EditerTournoi' value=$IdTournoi><input type='submit' class='click-ligne' value='Modifier'>";
		echo "</form></div></div>";
		
		if($EtatTournoi == 'Phase inscription') {
			
			/*
			-----------------
			Gérer la phase d'inscription + validation équipes
			-----------------
			*/
			
			//compter le nombre d'équipes inscrites et l'afficher
			$nbEquipesInscrites = 0;
			foreach($dbh->query("SELECT COUNT(*) AS nb FROM ListeEquipe WHERE AttenteInscription = 0 AND IdTournoi = $IdTournoi") as $row) {
				$nbEquipesInscrites = $row['nb'];
			}
			echo "$nbEquipesInscrites équipes inscrites (maximum $NbEquipeMaxTournoi)<br/>";
			
			//afficher les équipes inscrites et leur niveau
			foreach($dbh->query("SELECT Equipe.Nom, Equipe.Niveau FROM Equipe, ListeEquipe WHERE ListeEquipe.IdEquipe = Equipe.IdEquipe AND ListeEquipe.IdTournoi = $IdTournoi AND AttenteInscription = 0") as $row) {
				echo "[" . $row['Nom'] . "] -- niv " . $row['Niveau'] . "<br/>";
			}
			
			//chercher toutes les équipes en attente
			$sql = "SELECT Equipe.IdEquipe, Equipe.Niveau, Equipe.Nom FROM Equipe, ListeEquipe WHERE Equipe.IdEquipe=ListeEquipe.IdEquipe AND ListeEquipe.IdTournoi=$IdTournoi AND AttenteInscription=1";
			$nbEquipesAValider = 0;
			echo "<br/><h5>En attente de validation : </h5>";	
			echo "<form method=post action=GestionTournoi.php>";	
				
			//pour chaque, cases à cocher		
			foreach($dbh->query($sql) as $row) {
				$nbEquipesAValider++;
				echo "<input type='checkbox' name='equipesAValider[]' value='$IdTournoi" . "           " . $row['IdEquipe'] . "'>  ";
				echo "[";
				if(isset($row['Nom'])) {
					echo $row['Nom'];
				} else {
					echo "EquipeSansNom";
				}
				echo "] : niv " . $row['Niveau'] . "<br/>";	
			}
			
			//s'il y a des équipes en attentes, pouvoir les poster
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
			echo "<input type='hidden' name='Synthese' value='vrai'></form>";
			
			//bouton démarrer le tournoi
			if($nbEquipesInscrites != 0) {
				echo "<form method=post action=GestionTournoi.php><input type='hidden' name='DemarrerTournoi' value=$IdTournoi><input type='hidden' name='Synthese' value='vrai'><input type='submit' class='click' value='Démarrer le tournoi'></form>";
			}				
		} else {
			/*
			-----------------
			afficher la légende
			-----------------
			*/
			echo "<div style='position: absolute; top: 50px; left: 10px;'>Légende:</div>";
			echo "<div style='position: absolute; top: 80px; left:30px; border: 1px solid black; background-color: green; width: 20px; height: 20px;'><div class='tooltip2'><div class='tooltiptext2'>Rencontre entièrement remplie</div></div></div>";
			echo "<div style='position: absolute; top: 110px; left:30px; border: 1px solid black; background-color: red; width: 20px; height: 20px;'><div class='tooltip2'><div class='tooltiptext2'>Pas d'entrée d'arbre (remplissez les rencontres précédentes)</div></div></div>";
			echo "<div style='position: absolute; top: 140px; left:30px; border: 1px solid black; background-color: yellow; width: 20px; height: 20px;'><div class='tooltip2'><div class='tooltiptext2'>Il manque les détails de la rencontre</div></div></div>";
			echo "<div style='position: absolute; top: 170px; left:30px; border: 1px solid black; background-color: blue; width: 20px; height: 20px;'><div class='tooltip2'><div class='tooltiptext2'>Il manque le score</div></div></div>";
			/*
			-----------------
			construire la matrice de l'arbre 
			$matrice[h]['Nombre'] contient le nombre d'idarbre à la hauteur h
			$matrice[h][i]['IdArbre'] contient l'idarbre numéro i à la hauteur h
			$matrice[h][i]['Fils'] contient le fils de la case i
			$matrice[h][i]['Rencontre'] contient la rencontre de la case i
			$matrice[h][i]['Equipe1']
			$matrice[h][i]['Equipe2']
			-----------------
			*/
			foreach($dbh->query("SELECT MAX(Hauteur) AS max, MIN(Hauteur) AS min FROM Arbre WHERE Arbre.Tournoi = $IdTournoi") as $row) {
				$HauteurMax = $row['max'];	
				$HauteurMin = $row['min'];
			}
			for($h=$HauteurMax; $h >= $HauteurMin; $h--) {
				$matrice[$h] = array();
				foreach($dbh->query("SELECT COUNT(*) AS total FROM Arbre WHERE Arbre.Tournoi = $IdTournoi AND Hauteur = $h") as $row) {
					$matrice[$h]['Nombre'] = $row['total'];
				}
				//si on est à la hauteur max, on rentre tout l'arbre d'un coup
				if($h == $HauteurMax) {
					$recherche = $dbh->query("SELECT IdArbre, Fils, Rencontre, Equipe1, Equipe2 FROM Arbre WHERE Arbre.Tournoi = $IdTournoi AND Hauteur = $h");
					$i = 1;
					foreach($recherche as $row) {
						$matrice[$h][$i] = array();
						$matrice[$h][$i]['IdArbre'] = $row['IdArbre']; 
						$matrice[$h][$i]['Fils'] = $row['Fils'];
						$matrice[$h][$i]['Rencontre'] = $row['Rencontre'];
						$matrice[$h][$i]['Equipe1'] = $row['Equipe1'];
						$matrice[$h][$i]['Equipe2'] = $row['Equipe2'];
						$i++;
					}
				} else { //sinon on regarde un par un les enfants
					$i = 1;
					for($j=2; $j <= $matrice[$h+1]['Nombre'] ; $j+=2) {
						foreach($dbh->query("SELECT IdArbre, Fils, Rencontre, Equipe1, Equipe2 FROM Arbre WHERE IdArbre = " . $matrice[$h+1][$j]['Fils']) as $row) {
							$matrice[$h][$i] = array();
							$matrice[$h][$i]['IdArbre'] = $row['IdArbre']; 
							$matrice[$h][$i]['Fils'] = $row['Fils'];
							$matrice[$h][$i]['Rencontre'] = $row['Rencontre'];
							$matrice[$h][$i]['Equipe1'] = $row['Equipe1'];
							$matrice[$h][$i]['Equipe2'] = $row['Equipe2'];
							$i++;
						}
					}
				}
			}
			
			/*
			-----------------
			Afficher l'arbre complet en fonction de la hauteur max
			Codification des div: id = hauteur + numero
			ex: id = "116", 16e case de la hauteur 1 (demi-finale)
			-----------------
			*/
			$tailleDiv = 50;
			$hauteurDiv = 50;
			$separationDivLargeur = 10;
			$separationDivHauteur = 30;
			$total = $tailleDiv + $separationDivLargeur;
			$topAbsolute = 50;
			$leftAbsolute = 80;
			
			for($HauteurActuelle = $HauteurMax; $HauteurActuelle >= 0; $HauteurActuelle--) {
				for($i=1; $i <= pow(2, $HauteurActuelle); $i++) {
					//afficher les cadres
					$left = ($i - 1) * ($total * pow(2, ($HauteurMax - $HauteurActuelle))) + $total * pow(2, ($HauteurMax - $HauteurActuelle) - 1) - $total / 2 + $separationDivLargeur + $leftAbsolute;
					$top = $HauteurActuelle * ($hauteurDiv + $separationDivHauteur) + $tailleDiv + $topAbsolute;
					echo "<div id=\"CaseArbre" . $HauteurActuelle . $i . "\" style=\"border: 1px solid black; position: absolute; left:" . $left . "px; top:" . $top . "px; margin: 0; padding: 0; text-align: center; width: " . $tailleDiv . "px; height: " . $hauteurDiv . "px; font-size: 80%\" onClick='ouvrirChange(\"" . $HauteurActuelle . $i . "\")'>	</div>";
					//positionner les champs infos
					$topInfo = 50;
					$rightInfo = 50;
					$widthInfo = 400;
					$heightInfo = 200;
					echo "<div style='border: 1px solid black; position: absolute; top: " . $topInfo . "px; right: " . $rightInfo . "px; width: " . $widthInfo . "px; height: " . $heightInfo . "px; margin: 0; padding-left: 15px; padding-right: 15px; background-color: white; z-index: 30000' id='CaseInfo" . $HauteurActuelle . $i . "'>";
					/*
					------------------
					Remplir les informations du champ
					------------------
					*/
					echo "<br/>";
					$noScore = 0;
					$e1nom = "SansNom";
					$e2nom = "SansNom";
					if($HauteurActuelle < $HauteurMin) {
						echo "pas d'entrée d'arbre";
					} else {
						$e1nom = "SansNom";
						$e2nom = "SansNom";
						foreach($dbh->query("SELECT E1.Nom AS E1nom, E2.Nom AS E2nom, E1.Niveau AS nivE1, E2.Niveau AS nivE2 FROM Equipe E1, Equipe E2 WHERE E1.IdEquipe =" . $matrice[$HauteurActuelle][$i]['Equipe1'] . " AND E2.IdEquipe = " . $matrice[$HauteurActuelle][$i]['Equipe2']) as $row) {
							if($row['E1nom'] != NULL) $e1nom = $row['E1nom'];
							if($row['E2nom'] != NULL) $e2nom = $row['E2nom'];
							$nivE1 = $row['nivE1'];
							$nivE2 = $row['nivE2'];
						}
						//on raccourci si nécessaire
						$e1nom = strlen($e1nom) > 20 ? substr($e1nom, 0, 20) : $e1nom;
						$e2nom = strlen($e2nom) > 20 ? substr($e2nom, 0, 20) : $e2nom;
						if($nivE1 == -1 || $nivE2 == -1) {
							echo "Cette rencontre sert à équilibrer le tournoi<br/>";
							if($nivE1 != -1 || $nivE2 != -1) {
								echo "[";
								if($nivE1 != -1) {
									echo $e1nom;
								} else {
									echo $e2nom;
								}							
								echo "] la gagne automatiquement !";
							}
						} else {
							echo "[" . $e1nom . "] vs [" . $e2nom . "] <br/>";
							if($matrice[$HauteurActuelle][$i]['Rencontre'] == NULL) {
								echo "pas de rencontre créée<br/>";
							} else {
								foreach($dbh->query("SELECT DateRencontre, Lieu, Duree, TempsAdditionnel, EtatRencontre, ScoreEquipe1, ScoreEquipe2, Images, Vainqueur FROM Rencontre WHERE IdRencontre =" . $matrice[$HauteurActuelle][$i]['Rencontre']) as $row) {
									if($row['Vainqueur'] != NULL) {
										echo "Score : " . $row['ScoreEquipe1'] . " à " . $row['ScoreEquipe2'] . " ---> Gagnant : ";
										if($row['Vainqueur'] == $matrice[$HauteurActuelle][$i]['Equipe1']) {
											echo $e1nom;
										} else {
											echo $e2nom;
										}
										echo "<br/>";
									} else {
										echo "Scores manquants <br/>";
										$noScore = 1;
									}
									if($row['DateRencontre'] != NULL) {
										echo "Date: " . $row['DateRencontre'] . "<br/>";
									} else {
										echo "Date manquante <br/>";
									}
									if($row['Lieu'] != NULL) {
										echo "Lieu: " . $row['Lieu'] . "<br/>";
									} else {
										echo "Lieu manquant <br/>";
									}
									if($row['Duree'] != NULL) {
										echo "Duree: " . $row['Duree'] . " min<br/>";
									} else {
										echo "Duree manquante <br/>";
									}
									if($row['TempsAdditionnel'] != NULL) {
										echo "Temps additionnel: " . $row['TempsAdditionnel'] . " min<br/>";
									} else {
										echo "Temps additionnel manquant <br/>";
									}
								}
							}
						}
					}
					echo "</div>";
					/*
					------------------
					Champ de changement, s'affiche en cliquant sur une case
					------------------
					*/
					echo "<div style='border: 1px solid black; position: absolute; top: 5%; left: 5%; width: 90%; height: 90%; margin: 0; padding: 0; z-index: 4000000; background-color: lightgrey' id='CaseChange" . $HauteurActuelle . $i . "'>";
					//bouton de fermeture en haut à droite
					echo "<div style='position: absolute; top: 0; right: 0; width: 30px; height: 40px; font-size: 38px; line-height: 38px; padding: 0; font-weight: bold; color: red;' onClick='fermerChange(\"" . $HauteurActuelle . $i . "\")'>X</div>";
					//titre
					echo "<div style='text-align: center'>";
					echo "<h4>Editer la rencontre</h4><h5>$NomTournoi : " . hauteurEnMots($HauteurActuelle) . "</h5>";
					//afficher les informations si possible
					if($HauteurActuelle < $HauteurMin) {
						echo "<h5>Pas encore d'entrée d'arbre</h5><h5>Finissez d'abord de rentrer les scores de " . hauteurEnMots($HauteurActuelle+1) . "</h5></div>";
					} else {
						//regarder si une des deux équipes est l'équipe nulle
						if($nivE1 == -1 || $nivE2 == -1) {
							echo "<h5>Cette rencontre sert à équilibrer le tournoi, vous ne pouvez pas l'éditer</h5>";
							if($nivE1 != -1 || $nivE2 != -1) {
								echo "<h5>[";
								if($nivE1 != -1) {
									echo $e1nom;
								} else {
									echo $e2nom;
								}							
								echo "] la gagne automatiquement !";
							}
							echo "</div>";
						} else {
							echo "<h5>[$e1nom] affronte [$e2nom]</h5></div>";
							//éditer rencontre
							echo "<h5>Rentrer les détails de la rencontre</h5>";
							if($matrice[$HauteurActuelle][$i]['Rencontre'] == NULL) {
								//rien de rentré encore
								echo "<div class='alert alert-danger'>";
								echo "<form method=post>
										Date de la rencontre : <input type='date' name='DateRencontre' value='2021-01-01'><br/>
										Lieu : <input type='text' name='Lieu' value='Hic et Nunc'><br/>
										Durée (minutes) : <input type='number' name='Duree' value=90><br/>
										Image : <input type='text' name='Images' value='default.png'><br/>
										Temps Additionnel : <input type='number' name='TempsAdditionnel' value=0><br/>
										<input type='hidden' name='IdArbre' value=" . $matrice[$HauteurActuelle][$i]['IdArbre'] . ">
										<input type='hidden' name='Equipe1' value=" . $matrice[$HauteurActuelle][$i]['Equipe1'] . ">
										<input type='hidden' name='Equipe2' value=" . $matrice[$HauteurActuelle][$i]['Equipe2'] . ">
										<input type='hidden' name='RencontreValidation' value=-1>
										<input type='hidden' name='Synthese' value='vrai'>
										<input type='submit' class='click-ligne' value='Valider'>
									 </form></div>";
							} else {
								//déjà des informations à aller chercher pour les éditer
								foreach($dbh->query("SELECT DateRencontre, Lieu, Duree, Images, TempsAdditionnel FROM Rencontre WHERE IdRencontre=" . $matrice[$HauteurActuelle][$i]['Rencontre']) as $row) {
									echo "<div class='alert alert-success'>";
									echo "<form method=post>
											Date de la rencontre : <input type='date' name='DateRencontre' value=" . $row['DateRencontre'] . "><br/>
											Lieu : <input type='text' name='Lieu' value='" . $row['Lieu'] . "'><br/>
											Durée (minutes) : <input type='number' name='Duree' value=" . $row['Duree'] . "><br/>
											Image : <input type='text' name='Images' value='" . $row['Images'] . "'><br/>
											Temps Additionnel (minutes): <input type='number' name='TempsAdditionnel' value=" . $row['TempsAdditionnel'] . "><br/>
											<input type='hidden' name='IdArbre' value=" . $matrice[$HauteurActuelle][$i]['IdArbre'] . ">
											<input type='hidden' name='Equipe1' value=" . $matrice[$HauteurActuelle][$i]['Equipe1'] . ">
											<input type='hidden' name='Equipe2' value=" . $matrice[$HauteurActuelle][$i]['Equipe2'] . ">
											<input type='hidden' name='RencontreValidation' value=" . $matrice[$HauteurActuelle][$i]['Rencontre'] . ">
											<input type='hidden' name='Synthese' value='vrai'>
											<input type='submit' class='click-ligne' value='Modifier'>
										  </form></div>";
								}						
							}
							//éditer score
							echo "<h5>Rentrer les scores</h5>";
							if($matrice[$HauteurActuelle][$i]['Rencontre'] == NULL) {
								echo "Veuillez d'abord remplir les informations de la rencontre";
							} else {
								$Score1 = 0;
								$Score2 = 0;
								foreach($dbh->query("SELECT ScoreEquipe1, ScoreEquipe2 FROM Rencontre WHERE IdRencontre=" . $matrice[$HauteurActuelle][$i]['Rencontre']) as $row) {
									if($row['ScoreEquipe1'] != NULL) $Score1 = $row['ScoreEquipe1'];
									if($row['ScoreEquipe2'] != NULL) $Score2 = $row['ScoreEquipe2'];
								}
								echo "<form method=post action='GestionTournoi.php'>";
								echo "[$e1nom] <input type='number' size='3' name=score1 value='$Score1' min=0> - <input type='number' size='3' name=score2 value='$Score2' min=0> [$e2nom]";
								echo "<input type='hidden' name='IdRencontreScore' value=" . $matrice[$HauteurActuelle][$i]['Rencontre'] . "><input type='hidden' name='Equipe1' value=" . $matrice[$HauteurActuelle][$i]['Equipe1'] . "><input type='hidden' name='Equipe2' value=" . $matrice[$HauteurActuelle][$i]['Equipe2'] . "><input type='hidden' name='IdTournoi' value=$IdTournoi><input type='hidden' name='Synthese' value='vrai'><input type='submit' class='click' value='Valider'></form>";
							}
						}
					}
					echo "</div>";
					/*
					------------------
					css du hover et position des traits de jonction
					------------------
					*/
					//hover
					echo "<style>#CaseArbre" . $HauteurActuelle . $i . ":hover + #CaseInfo" . $HauteurActuelle . $i . "{display: inline-block;}#CaseInfo" . $HauteurActuelle . $i . "{display:none;}#CaseChange" . $HauteurActuelle . $i . "{display:none;}";
					//couleur
					echo "#CaseArbre" . $HauteurActuelle . $i . "{background-color:";
					if($HauteurActuelle < $HauteurMin) {
						//pas d'entrée d'arbre
						echo "red";
					} else if ($matrice[$HauteurActuelle][$i]['Rencontre'] == NULL) {
						//manque rencontre
						echo "yellow";
					} else if ($noScore == 1) {
						//manque score
						echo "blue";
					} else {
						//tout bon
						echo "green";	
					}
					echo ";}</style>";
					//afficher les traits de jonction
					if($HauteurActuelle != $HauteurMax) {
						$height = ($separationDivHauteur / 2);
						$width = pow(2, ($HauteurMax - $HauteurActuelle))* ($total / 2);
						$top = $HauteurActuelle * ($hauteurDiv + $separationDivHauteur) + 2*$tailleDiv + $height + $topAbsolute;
						$left = ($i - 1) * ($total * pow(2, ($HauteurMax - $HauteurActuelle))) + $total * pow(2, ($HauteurMax - $HauteurActuelle) - 2) + ($separationDivLargeur/2) + $leftAbsolute;
						echo "<div style='border-top: 1px solid green; border-left: 1px solid green; border-right: 1px solid green; position: absolute; left: " . $left . "px; top: " . $top . "px; margin: 0; padding: 0; width: " . $width . "px; height:" . $height . "px'></div>"; 
						$top -= $height;
						$left = ($i - 1) * ($total * pow(2, ($HauteurMax - $HauteurActuelle))) + $total * pow(2, ($HauteurMax - $HauteurActuelle) - 1) + ($separationDivLargeur / 2) + $leftAbsolute;
						echo "<div style='border-left: 1px solid green; position: absolute; left: " . $left . "px; top: " . $top . "px; margin: 0; padding: 0; width: 2px; height:" . $height . "px'></div>";
					}
				}
			}
		}
	}	
}
}
echo "</div>";

?>

<script>
//afficher l'interface d'édition
function ouvrirChange(x) {
	document.getElementById('CaseChange' + x).style.display = "inline-block";
}
//fermer l'interface d'édition
function fermerChange(x) {
	document.getElementById('CaseChange' + x).style.display = "none";
}
</script>
