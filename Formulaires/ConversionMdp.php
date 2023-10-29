<?php
session_start();
include("connexion.php");
date_default_timezone_set('Europe/Paris');

try {
  $bdd = new PDO('mysql:host=localhost;dbname=suricates;charset=UTF8', $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
  echo $e->getMessage();
  die("connexion Impossible !");
}

$titreOnglet="Hach MDP";
$linkPage="../Formulaires/ConversionMdp.php";
$titrePage="Hachage de mot de passe";

?>
<!doctype html>
                    <?php
                    include("../CadreStatique/skel1.php");
                    ?>
<form action="#" method="Post">
<input id="mdp" type="text" name="mdp" placeholder="Mdp à hacher"><label for="mdp"></label>
<button type="submit">Valider</button>
</form>
<?php
if(isset($_POST['mdp'])){
    echo password_hash($_POST['mdp'], PASSWORD_DEFAULT);
}
?>
                    <?php
                    include("../CadreStatique/skel2.php");
                    ?>
</html>



