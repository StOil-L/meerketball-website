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

$titreOnglet = "Accueil";
$linkPage = "../CadreStatique/index1.php";
$titrePage = "Accueil";

?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");
?>
<div class="row">
    <div id="carousel2Div" class="col-12">
        <?php
        include("../CadreStatique/carousel/carousel2.php");
        ?>
    </div>
        <div id="carousel2RespDiv" class="col-12">
        <?php
        include("../CadreStatique/carousel/carousel2Resp.php");
        ?>
        
    </div>
    <div class="col-12">
        <?php
        include("../Formulaires/ListeDesTournois.php");
        ?>
    </div>
</div>
<?php
include("../CadreStatique/skel2.php");
?>

</html>