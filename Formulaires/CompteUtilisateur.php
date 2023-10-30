<?php
session_start();
include("../CadreStatique/connexion.php");
date_default_timezone_set('Europe/Paris');

try {
  $dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
  echo $e->getMessage();
  die("connexion Impossible !");
}

$titreOnglet="CompteUtilisateur";
$linkPage="../Formulaires/CompteUtilisateur.php";
$titrePage="CompteUtilisateur";

?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");
?>

<?php 

echo "<h1>  Compte Utilisateur</h1>";
$ID = $_SESSION['historique']['idLogin'];
$sql = "SELECT LoginU, EMail, DatesInscription, Nom, Prenom, RoleU, OptionDalt FROM Utilisateur WHERE IdUtilisateur=$ID";
$result = $dbh->query($sql);


echo "<h2>Informations personnelles de ". $_SESSION['historique']['login'] ."</h2>";
if ($row = $result->fetch()) // S'il y a un ou des résultat.s
{
  do {
    if ($row['LoginU'] !== NULL)
    {
      echo "Identifiant : " . $row['LoginU'];
    }
    else
    {
      echo " - ";
    }
    echo "<br/>";

    if ($row['Prenom'] !== NULL)
    {
      echo "Prénom : " . $row['Prenom'];
    }
    else
    {
      echo " - ";
    }
    echo "<br/>";

    if ($row['Nom'] !== NULL)
    {
      echo "Nom : " . $row['Nom'];
    }
    else
    {
      echo " - ";
    }
    echo "<br/>";

    if ($row['EMail'] !== NULL)
    {
      echo "Adresse e-mail : " . $row['EMail'];
    }
    else
    {
      echo " - ";
    }
    echo "<br/>";

    if ($row['RoleU'] !== NULL)
    {
      echo "Rôle :  " . $row['RoleU'];
    }
    else
    {
      echo " - ";
    }
    echo "<br/>";

    if ($row['OptionDalt'] == 1)
    {
      echo "Option daltonisme : Activée";
    }
    else
    {
      echo "Option daltonisme : Désactivée";
    }
    echo "<br/>";

    if ($row['DatesInscription'] !== NULL)
    {
      echo "Compte existant depuis " . $row['DatesInscription'];
    }
    else
    {
      echo " - ";
    }
    echo "<br/>";

  } while ($row = $result->fetch());

  echo "<form action='ModifInfosPerso.php' method='post'>
  <button class='click' type='submit' name='Modifier'>Modifier mes informations</button>
  </form><br/><br/>";
  echo "<form action='Desinscription.php' method='post'>
  <button class='click' type='submit' name='Supprimer'>Supprimer mon compte</button>
  </form>";
  echo "Veuillez noter que la suppression de votre compte est irreversible et entraîne une perte d'accès immédiate à vos données.";

}
else
{
  echo "<div class='text-sans-classe'>Vous devez être un utilisateur inscrit pour avoir accès à un compte.</div>"; // S'il n'y a aucun tournoi, on l'affiche
}
?>

<?php
  include("../CadreStatique/skel2.php");
?>
</html>
