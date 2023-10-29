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

$titreOnglet = "Désinscription";
$linkPage = "../Formulaires/Desinscription.php";
$titrePage = "Désinscription";

?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");


$login = $_SESSION['historique']['login'];

if(!isset($_POST['sur'])){
    if(!isset($_POST['surDeChezSur'])){
          echo "<form method=post action=Desinscription.php >
          <br/>";
          echo "Êtes vous sûr de vouloir supprimer votre compte ? :
          <button class='click-ligne' type=submit name=sur>Valider</button>
          </form>";
      }
  }
if (isset($_POST['sur'])) {

  echo "<form method=post action=Desinscription.php>
  <br/>";
  echo "Veuillez saisir votre mot de passe : <input type='password' name='mdp' >
  <button class='click-ligne' name='surDeChezSur'>Valider </button>
  </form>";
}

  if(isset($_POST['surDeChezSur'])){
     $sql="SELECT MotDePasse FROM Utilisateur WHERE LoginU='$login'";
  
     foreach($dbh->query($sql) as $row){

        if (password_verify ($_POST['mdp'] , $row['MotDePasse'])){


            $dbh->exec("DELETE FROM Utilisateur WHERE LoginU='$login'");

                  echo "<div class='alert alert-success'>Votre Compte a été supprimé avec succès</div>";
                  echo "<meta http-equiv='refresh' content='2;URL=Deconnexion.php?redirection=Deconnexion.php'/>";

              }
              else {
                echo "<form method=post action=Desinscription.php >
                <br/>";
                echo "Êtes vous sûr de vouloir supprimer votre compte ? :
                <button class='click-ligne' type=submit name=sur>Valider</button>
                </form>";
                echo "<div class='alert alert-danger'> Mot de passe non reconnu </div>";
                
            }
              }
  }

include("../CadreStatique/skel2.php");
?>

</html>
