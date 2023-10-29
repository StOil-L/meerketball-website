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

$titreOnglet = "Admin";
$linkPage = "../AutresPages/PageAdmin.php";
$titrePage = "Page d'administration";

?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");
?>
<ul>
    <li><a href="../Formulaires/CreationTournoi.php">Formulaire 1 : Creation de tournois</a></li>
    <li><a href="../Formulaires/Preinscription.php">Formulaire 2 : Préinscription aux tournois</a></li>
    <li><a href="../Formulaires/GestionTournoi.php">Formulaire 3 : Gestion des tournois</a></li>
    <li><a href="../Formulaires/CreationTournoiAleatoire.php">Formulaire 4 : Création d'un tournoi aléatoire</a></li>
    <li><a href="../Formulaires/ListeDesTournois.php">Formulaire 5 : Liste des tournois</a></li>
</ul>
<br>
<p>Autres : </p>
<br>
<ul>
    <li><a href="../Formulaires/Equipe.php">Création équipes</a></li>
    <li><a href="../Formulaires/ConversionMdp.php">Hachage de mots de passe</a></li>
    <li><a href="../Formulaires/Inscription.php">Inscription</a></li>
    <li><a href="../Formulaires/Desinscription.php">Desinscription</a></li>
    <li><a href="../Formulaires/LoginUtilisateur.php"onclick="savePagePrec2()">Login utilisateur</a></li>
    <li><a href="../Formulaires/Deconnexion.php">Deconnexion</a></li>
    <li><a href="../Formulaires/Options.php">Options compte utilisateur</a></li>
    <li><a href="../Formulaires/Demonstration.php" onclick="savePagePrec2()">Réinitialisation en vue de démonstration de la BDD</a> </li>
</ul>
<?php
include("../CadreStatique/skel2.php");
?>
<script>
function savePagePrec2(){
        sessionStorage.setItem("pagePrec", location.href);
    }
    
</script>
</html>
