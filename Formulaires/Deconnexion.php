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

$titreOnglet="Deconnexion";
$linkPage="../Formulaires/Deconnexion.php";
$titrePage="Deconnexion";

?>
<!doctype html>
                    <?php
                    include("../CadreStatique/skel1.php");
                    
$_SESSION = array();
session_destroy();
?>
<script>
alert('Deconnection réussie');
window.location = "../CadreStatique/index1.php"
</script>
<?php


                    
                    include("../CadreStatique/skel2.php");
                    ?>
</html>
