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

$titreOnglet="Tournois";
$linkPage="../AutresPages/Tournois.php";
$titrePage="Tournois";

?>
<!doctype html>
                    <?php
                    include("../CadreStatique/skel1.php");
                    ?>
<ul>
    <li><a href="../Formulaires/CreationTournoi.php">Creation de tournois</a></li>
    <li><a href="../Formulaires/Preinscription.php">Préinscription aux tournois</a></li>
    <li><a href="../Formulaires/GestionTournoi.php">Gestion des tournois</a></li>
    <li><a href="../Formulaires/CreationTournoiAleatoire.php">Création d'un tournoi aléatoire</a></li>
    <li><a href="../Formulaires/ListeDesTournois.php">Liste des tournois</a></li>
    <li><a href="../Formulaires/Equipe.php">Création d'une équipe de 5 joueurs</a></li>

</ul>
                    <?php
                    include("../CadreStatique/skel2.php");
                    ?>
</html>
