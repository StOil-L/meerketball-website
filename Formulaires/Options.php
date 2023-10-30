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

$titreOnglet="Options";
$linkPage="../Formulaires/Options.php";
$titrePage="Options";

?>
<!doctype html>
                    <?php
                    include("../CadreStatique/skel1.php");
                    ?>

                    <?php
                    include("../CadreStatique/skel2.php");
                    ?>
</html>
