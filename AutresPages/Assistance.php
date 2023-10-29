<?php
session_start();
include("../CadreStatique/connexion.php");
date_default_timezone_set('Europe/Paris');

try {
  $bdd = new PDO('mysql:host=localhost;dbname=suricates;charset=UTF8', $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
  echo $e->getMessage();
  die("connexion Impossible !");
}

$titreOnglet="Assistance";
$linkPage="../AutresPages/Assistance.php";
$titrePage="Assistance";

?>
<!doctype html>
  <?php
    include("../CadreStatique/skel1.php");
  ?>
  <h1>Assistance</h1>
  <div>
    Avant de contacter le support technique, vous pouvez lire la <a href='FAQ.php'>Foire Aux Questions</a>.<br/>
    En cas d'incident ou de problème technique, veuillez envoyer un mail à cette adresse : <a href='mailto:?to=support-technique@meerketball.com&subject=Support Meerketball%20-%20[Intitulé%20de%20votre%20problème]&body=[Description%20de%20votre%20problème]'>support-technique@meerketball.com</a>.<br/>  
    Veuillez noter que <a href='../CadreStatique/index1.php'>Meerketball</a> n'est pas responsable d'éventuelles annulations de tournois ou d'incidents se produisant durant les tournois.
  </div>
  <?php
    include("../CadreStatique/skel2.php");
  ?>
</html>