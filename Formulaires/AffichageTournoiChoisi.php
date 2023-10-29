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

$titreOnglet = "Déroulement";
$linkPage = "";
$titrePage = "";

?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");
if(isset($_POST['Tournoi'])) // On teste si ce formulaire reçoit bien un IdTournoi (passé dans l'url)
{
  $ID = intval ($_POST['Tournoi']); // Il faut qu'IdTournoi soit bien un entier pour fonctionner dans les requêtes sql
  $sql = "SELECT COUNT(*) AS Nombre FROM Rencontre, Tournoi WHERE Tournoi.IdTournoi=$ID AND Tournoi.IdTournoi=Rencontre.Tournoi AND Rencontre.DateRencontre < NOW()"; // Selectionner le nom du tournoi correspondant à l'IdTournoi
  $result = $conn->query($sql);


  if ($row = $result->fetch()) // S'il y a un ou des résultat.s
  {
    do
    {
      if ($row['Nombre'] != 0) // S'il y a au moins une rencontre
      {
        $sql = "SELECT Nom, IdTournoi FROM Tournoi WHERE Tournoi.IdTournoi=$ID"; // Selectionner le nom du tournoi correspondant à l'IdTournoi
        $result = $conn->query($sql);
        if ($row = $result->fetch()) // S'il y a un ou des résultat.s
        {
          do
          {
            if ($row['Nom'] !== NULL) // Si le tournoi a un nom
            {
              echo "<h2 id='littitle'>Déroulement du tournoi ".$ID." : ".$row['Nom']."</h2>"; // Afficher son numéro et son nom (garder le numéro ?)
            }
            else // Si le tournoi n'a pas de nom
            {
              echo "<h2 id='littitle'>Déroulement du tournoi ".$ID."</h2>"; // Afficher son numéro
            }
          }
          while($row = $result->fetch());
        }
        else
        {
        echo "<div class='alert alert-danger'>Erreur : le nom du tournoi n'a pas pu être récupéré</div>"; // Si on ne peut pas récupérer de nom, afficher l'erreur
        }

        echo "<ul>"; // Début de la liste des rencontres
        $sql = "SELECT Arbre.Rencontre, Arbre.IdArbre, Arbre.Hauteur, Arbre.Equipe1, Arbre.Equipe2 FROM Tournoi, Rencontre, Arbre WHERE Tournoi.IdTournoi=($ID) AND Tournoi.IdTournoi=Rencontre.Tournoi AND Rencontre.IdRencontre = Arbre.Rencontre AND Rencontre.DateRencontre<NOW() GROUP BY Tournoi.IdTournoi, Rencontre.IdRencontre, Arbre.IdArbre ORDER BY Arbre.Hauteur ASC, Rencontre.DateRencontre DESC"; $result=$conn->query($sql); // Récupérer différentes informations de l'arbre du tournoi
        if ($row = $result->fetch()) // S'il y a un ou des résultat.s
        {
          do
          {
            if ($row['Equipe1'] != NULL and $row['Equipe2'] != NULL)
            {
              // On fait plusieurs requêtes pour chaque arbre (d'où l'imbrication), pour trouver :
              $EQ1 = $row['Equipe1']; // Le nom de l'équipe 1 (éviter l'Id qui n'a pas de sens)
              $EQ2 = $row['Equipe2']; // Le nom de l'équipe 2 (éviter l'Id qui n'a pas de sens)
              $Ren = $row['Rencontre']; // L'Id du vainqueur et les scores
              $sqlEQ1 = "SELECT Nom FROM Equipe WHERE IdEquipe =($EQ1)";
              $sqlEQ2 = "SELECT Nom FROM Equipe WHERE IdEquipe =($EQ2)";
              $sqlRen = "SELECT Vainqueur, ScoreEquipe1, ScoreEquipe2 FROM Rencontre WHERE IdRencontre =($Ren)";
              $resultEQ1 = $conn->query($sqlEQ1);
              $resultEQ2 = $conn->query($sqlEQ2);
              $resultRen = $conn->query($sqlRen);
              if ($rowEQ1 = $resultEQ1->fetch() and $rowEQ2 = $resultEQ2->fetch() and $rowRen = $resultRen->fetch()) // S'il y a un ou des résultat.s
              {
                do
                {
                  // On fait encore une (dernière) imbrication pour trouver le nom de l'équipe gagnante (encore pour éviter de garder l'Id)
                  $V = $rowRen['Vainqueur'];
                  $sqlV = "SELECT Nom FROM Equipe WHERE IdEquipe=($V)";
                  $resultV = $conn->query($sqlV);
                  if ($rowV = $resultV->fetch()) // S'il y a un ou des résultat.s
                  {
                    do
                    {
                      // On arrive à la partie où on affiche toutes les informations qu'on a recueillies pour chaque arbre
                      // On traite les cas où il manque un ou des nom.s d'équipe, à la fois pour afficher la rencontre et le vainqueur
                      // Affichage de l'étape
                      if($row['Hauteur'] == 0)
                      {
                        echo "<li><strong>Finale</strong></li>";
                      }
                      else if($row['Hauteur'] == 1)
                      {
                        echo "<li><strong>Demi-finale</strong></li>";
                      }
                      else if($row['Hauteur'] == 2)
                      {
                        echo "<li><strong>Quart de finale</strong></li>";
                      }
                      else if($row['Hauteur'] == 3)
                      {
                        echo "<li><strong>Huitième de finale</strong></li>";
                      }
                      else if($row['Hauteur'] == 4)
                      {
                        echo "<li><strong>Seizième de finale</strong></li>";
                      }
                      else if($row['Hauteur'] == 5)
                      {
                        echo "<li><strong>Trente-deuxième de finale</strong></li>";
                      }
                      else
                      {
                        echo "<li><strong>Phase ".$row['Hauteur']."</strong></li>";
                      }

                      // Affichage de la rencontre
                      if ($rowEQ1['Nom'] !== NULL and $rowEQ2['Nom'] !== NULL)
                      {
                        echo "<div class='sous-li'>Équipe ".$row['Equipe1']." : ".$rowEQ1['Nom']." VS Équipe ".$row['Equipe2']." : ".$rowEQ2['Nom']."<br /></div>"; // Afficher un résultat comme élément de la liste
                      }
                      else if ($rowEQ1['Nom'] === NULL)
                      {
                        echo "<div class='sous-li'>Équipe ".$row['Equipe1']." VS Équipe ".$row['Equipe2']." : ".$rowEQ2['Nom']."<br /></div>"; // Afficher un résultat comme élément de la liste
                      }
                      else if ($rowEQ2['Nom'] === NULL)
                      {
                        echo "<div class='sous-li'>Équipe ".$row['Equipe1']." : ".$rowEQ1['Nom']." VS Équipe ".$row['Equipe2']."<br /></div>"; // Afficher un résultat comme élément de la liste
                      }
                      else
                      {
                        echo "<div class='sous-li'>Équipe ".$row['Equipe1']." VS Équipe ".$row['Equipe2']."<br /></div>"; // Afficher un résultat comme élément de la liste
                      }

                      // Affichage des scores
                      echo "<div class='sous-li'>Scores : ".$rowRen['ScoreEquipe1']." - ".$rowRen['ScoreEquipe2']."</div>";

                      // Affichage du vainqueur
                      if ($rowV['Nom'] === NULL)
                      {
                        echo "<div class='sous-li'>Vainqueur : Équipe ".$rowRen['Vainqueur']."<br /><br /></div>";
                      }
                      else
                      {
                        echo "<div class='sous-li'>Vainqueur : Équipe ".$rowRen['Vainqueur']." - ".$rowV['Nom']."<br /><br /></div>";
                      }
                    }
                    while($rowV = $resultV->fetch());
                  }
                  else
                  {
                    echo "<div class='alert alert-danger'>Erreur : le nom du vainqueur n'a pas pu être récupéré</div>"; // Si on ne peut pas récupérer de nom, afficher l'erreur
                  }
                }
                while ($rowEQ1 = $resultEQ1->fetch() and $rowEQ2 = $resultEQ2->fetch() and $rowRen = $resultRen->fetch());
              }
              else
              {
                echo "<div class='alert alert-danger'>Erreur : les noms des équipes n'ont pas pu être récupérés</div>"; // Si on ne peut pas récupérer de nom, afficher l'erreur
              }
            }
          }
          while($row = $result->fetch());
        }
        else
        {
          echo "Il n'y a pas encore eu de rencontre dans ce tournoi."; // Afficher qu'il n'y a pas de résultat
          // Quand je reçois cette erreur, ça veut dire que j'ai mal rempli la table Arbre ou que j'ai ajouté des tournois sans re-remplir
        }
        echo "</ul>"; // Fin de la liste des rencontres

        // Récupérer et afficher la date de la prochaine rencontre (pour les tournois en cours)
        $sql = "SELECT MIN(Rencontre.DateRencontre) AS Prochaine FROM Rencontre, Tournoi WHERE Tournoi.IdTournoi=($ID) AND Tournoi.IdTournoi=Rencontre.Tournoi AND Rencontre.DateRencontre > NOW()";
        // La prochaine rencontre est la date la plus proche mais qui est plus tard que maintenant
        $result = $conn->query($sql); // Récupérer différentes informations de l'arbre du tournoi
        if ($row = $result->fetch()) // S'il y a un ou des résultat.s
        {
          do
          {
            if ($row['Prochaine'] !== NULL)
            {
              echo "<div class='text-sans-classe'>Ce tournoi est encore en cours ! <br /></div>"; // Si le tournoi est en cours, il y a une date de prochaine rencontre
              echo "<div class='text-sans-classe'>Date de la prochaine rencontre : ".$row['Prochaine']."</br></div>"; // On affiche cette date
            }
            else
            {
              echo "<div class='text-sans-classe'>Ce tournoi est terminé !<br /></div>"; // Si le tournoi est terminé, il n'y a pas de date de prochaine rencontre
            }
          }
          while($row = $result->fetch());
        }
        else
        {
          echo "<div class='alert alert-danger'>Erreur : la date de la prochaine rencontre n'a pas pu être récupérée</div>"; // Si la date ne peut être récupérée, afficher l'erreur
        }

        echo "<form method='post' action='AffichageTournoi.php'><input type='hidden' name='afficherTournoi' value=$ID><button class='click' type='submit' name='Tournoi'>Voir sous forme d'arbre</button>";
      }
      else // S'il n'y a aucune rencontre
      {
        $sql = "SELECT Nom, IdTournoi FROM Tournoi WHERE Tournoi.IdTournoi=$ID"; // Selectionner le nom du tournoi correspondant à l'IdTournoi
        $result = $conn->query($sql);
        if ($row = $result->fetch()) // S'il y a un ou des résultat.s
        {
          do
          {
            if ($row['Nom'] !== NULL) // Si le tournoi a un nom
            {
              echo "<h2 id='littitle'>Déroulement du tournoi ".$ID." : ".$row['Nom']."</h2>"; // Afficher son numéro et son nom (garder le numéro ?)
            }
            else // Si le tournoi n'a pas de nom
            {
              echo "<h2 id='littitle'>Déroulement du tournoi ".$ID."</h2>"; // Afficher son numéro
            }
          }
          while($row = $result->fetch());
        }
        else
        {
        echo "<div class='alert alert-danger'>Erreur : le nom du tournoi n'a pas pu être récupéré</div>"; // Si on ne peut pas récupérer de nom, afficher l'erreur
        }

        $sql = "SELECT MIN(Rencontre.DateRencontre) AS Premiere FROM Rencontre, Tournoi WHERE Tournoi.IdTournoi=($ID) AND Tournoi.IdTournoi=Rencontre.Tournoi AND Rencontre.DateRencontre > NOW()";
        // La prochaine rencontre est la date la plus proche mais qui est plus tard que maintenant
        $result = $conn->query($sql); // Récupérer différentes informations de l'arbre du tournoi
        if ($row = $result->fetch()) // S'il y a un ou des résultat.s
        {
          do
          {
            if ($row['Premiere'] !== NULL)
            {
              echo "<div class='text-sans-classe'>Ce tournoi n'a pas encore de rencontre.<br /></div>"; // Si le tournoi est en cours, il y a une date de prochaine rencontre
              echo "<div class='text-sans-classe'>Date de la première rencontre : ".$row['Premiere']."</br></div>"; // On affiche cette date
            }
            else
            {
              echo "<div class='text-sans-classe'>Ce tournoi n'a aucune rencontre.<br /></div>"; // Si le tournoi est terminé, il n'y a pas de date de prochaine rencontre
            }
          }
          while($row = $result->fetch());
        }
        else
        {
          echo "<div class='alert alert-danger'>Erreur : la date de la première rencontre n'a pas pu être récupérée</div>"; // Si la date ne peut être récupérée, afficher l'erreur
        }
      }
    }
    while($row = $result->fetch());
  }
  else
  {
  echo "<div class='alert alert-danger'>Erreur : le nombre de rencontres n'a pas pu être récupéré</div>"; // Si on ne peut pas récupérer de nom, afficher l'erreur
  }
}
else
{
echo "<div class='alert alert-danger'>Erreur : le formulaire doit recevoir un tournoi identifié par son IdTournoi<br /></div>"; // Si le formulaire ne reçoit pas un IdTournoi, afficher l'erreur
}

echo "<br/><a href='../CadreStatique/index1.php'><br />Retour à la liste</a>";

?>

<br />
<?php
include("../CadreStatique/skel2.php");
?>

</html>