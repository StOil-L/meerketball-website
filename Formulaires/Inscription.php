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

$titreOnglet = "Inscription";
$linkPage = "../Formulaires/Inscription.php";
$titrePage = "Inscription";

?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");
?>
<br />
<form method="post" action="Inscription.php">
    <div class='info'>Pseudo : <input type="text" name="pseudo" pattern='[A-Za-z0-9À-ÿ]*'></div>
    <div class='info'>Mot de passe : <input type="password" name="mdp"></div>
    <div class='info'>Retaper le mot de passe : <input type="password" name="mdpbis"></div>
    <div class='info'>Mail : <input type="email" name="mail"></div>
    <div class='info'>Nom : <input type="text" name="nom"></div>
    <div class='info'>Prenom : <input type="text" name="prenom"></div>
    <div class='info'>Mode daltonien ? : <input type="checkbox" value=1 name="daltonien"></div>
    <?php
    if (isset($_POST['daltonien'])) {
        $_SESSION['historique']['dalt'] = $_POST['daltonien'];
    }
    
    ?>
    <br />
    <input class='click' type="submit" value="S'inscrire" name="inscription">
</form>

<?php
echo "<br/>";
$dalt='';
if(isset($_SESSION['historique']['dalt'])){
    $dalt = $_SESSION['historique']['dalt'];
}

if(!isset($_POST['daltonien'])){
     $dalt=0;
}
if (isset($_POST['inscription'])) {

    if (isset($_POST['pseudo'], $_POST['mdp'], $_POST['mdpbis'], $_POST['mail'], $_POST['nom'], $_POST['prenom'])) {

        if ($_POST['pseudo'] == '') {

            echo "<div class='alert alert-danger'>Veuillez saisir un pseudo valide</div> ";
        }

        if ($_POST['nom'] == '') {

            echo "<div class='alert alert-danger'>Veuillez saisir un nom valide</div> ";
        }

        if ($_POST['prenom'] == '') {

            echo "<div class='alert alert-danger'>Veuillez saisir un prenom valide</div> ";
        } else {

            if (($_POST['mdp'] == '') OR ($_POST['mdp']!=$_POST['mdpbis'])) {

            echo "<div class='alert alert-danger'>Veuillez saisir un password valide</div> ";
            } else {
                if ($dalt != 1) {
                    $dalt = 0;
                }
                $mdpHache = password_hash($_POST['mdp'], PASSWORD_DEFAULT);

                $sql = ("SELECT LoginU, MotDePasse FROM Utilisateur ");
                $identique = 0;
                foreach ($dbh->query($sql) as $row) {
                    if ($_POST['pseudo'] == $row['LoginU']) {
                        $identique = 1;
                    }
                }
                if ($identique == 1) {
                    echo "<div class='alert alert-danger'> Ce Login n'est pas disponible </div>";
                } else {
                    $dbh->exec("INSERT INTO Utilisateur(loginU,MotDePasse, EMail,DatesInscription,Nom ,Prenom,RoleU,OptionDalt ) VALUES('$_POST[pseudo]','$mdpHache','$_POST[mail]',CURRENT_DATE(),'$_POST[nom]','$_POST[prenom]','Utilisateur',$dalt )");
                    echo "<div class='alert alert-success'> Votre compte a été créé avec succès !</div>";
                    $_SESSION['historique']['login'] = $_POST['pseudo'];
                    $_SESSION['historique']['RoleU'] = "Utilisateur";
                    $_SESSION['historique']['idLogin'] = $dbh->lastInsertId();
                    ?>
                    <script>
                        alert('Inscription réussie');
                        window.location = "../CadreStatique/index1.php"
                    </script>
                    <?php
                }
            }    
        }
    }
}



include("../CadreStatique/skel2.php");
?>

</html>
