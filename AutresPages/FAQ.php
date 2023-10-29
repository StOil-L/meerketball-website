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

$titreOnglet="FAQ";
$linkPage="../AutresPages/FAQ.php";
$titrePage="Foire aux questions";

?>
<!doctype html>
  <?php
    include("../CadreStatique/skel1.php");
  ?>
    <h1>Foire Aux Questions</h1>
    <div id='sommaire'>Sommaire</div>
    <ul>
      <li id='MODELEli'><a href='#MODELErep'>MODÈLE</a></li>
        <ul>
          <li><a href='#MODELEQ1'>Question 1</a></li>
          <li><a href='#MODELEQ2'>Question 2</a></li>
          <li><a href='#MODELEQ3'>Question 3</a></li>
        </ul>
      </li>
      <li id='RUli'><a href='#RUrep'>Rôles des utilisateurs</a></li>
        <ul>
          <li><a href='#RUQ1'>Quels sont les différents rôles ?</a></li>
          <li><a href='#RUQ2'>Que peut faire un utilisateur et comment le devenir ?</a></li>
          <li><a href='#RUQ3'>Que peut faire un joueur et comment le devenir ?</a></li>
          <li><a href='#RUQ4'>Que peut faire un capitaine d'équipe et comment le devenir ?</a></li>
          <li><a href='#RUQ5'>Que peut faire un gestionnaire et comment le devenir ?</a></li>
          <li><a href='#RUQ6'>Que peut faire un administrateur et comment le devenir ?</a></li>
        </ul>
      </li>
      <li id='OTli'><a href='#OTrep'>Organisation de tournois</a></li>
        <ul>
          <li><a href='#OTQ1'>Question 1</a></li>
          <li><a href='#OTQ2'>Question 2</a></li>
          <li><a href='#OTQ3'>Question 3</a></li>
        </ul>
      </li>
      <li id='CEli'><a href='#CErep'>Constitution d'Équipe</a></li>
        <ul>
          <li><a href='#CEQ1'>Question 1</a></li>
          <li><a href='#CEQ2'>Question 2</a></li>
          <li><a href='#CEQ3'>Question 3</a></li>
        </ul>
      </li>
    </ul>

    <div id='MODELErep'><strong>MODÈLE</strong>
      <p id='MODELEQ1'>MODÈLE - Question 1<br/>Réponse à la question 1<br/><a href='#sommaire'>Retour au sommaire</a></p>
      <p id='MODELEQ2'>MODÈLE - Question 2<br/>Réponse à la question 2<br/><a href='#sommaire'>Retour au sommaire</a></p>
      <p id='MODELEQ3'>MODÈLE - Question 3<br/>Réponse à la question 3<br/><a href='#sommaire'>Retour au sommaire</a></p>
    </div>

    <div id='RUrep'><strong>Rôles des utilisateurs</strong>
      <p id='RUQ1'>Quels sont les différents rôles et comment le devenir ?<br/>
        Il existe différents rôles pour les utilisateurs de <a href='../CadreStatique/index1.php'>Meerketball</a>, pour connaître les spécifictés de chacun d'entre eux, vous pouvez cliquer sur leur nom :
          <ul>
            <li><a href='#RUQ2'>Utilisateur non joueur</a></li>
            <li><a href='#RUQ3'>Utilisateur joueur</a></li>
            <li><a href='#RUQ4'>Capitaine d'équipe</a></li>
            <li><a href='#RUQ5'>Gestionnaire</a></li>
            <li><a href='#RUQ6'>Administrateur</a></li>
          </ul>
        <a href='#sommaire'>Retour au sommaire</a>
      </p>
      <p id='RUQ2'>Que peut faire un utilisateur non joueur et comment le devenir ?<br/>
      L'utilisateur de <a href='../CadreStatique/index1.php'>Meerketball</a> n'a aucun rôle particulier, la seule chose qui le différencie d'un internaute lambda est son inscription. Il a accès aux parties publiques du site et peut donc suivre l'actualité des tournois. Pour devenir utilisateur, vous pouvez vous inscrire ici : <a href='../Formulaires/Inscription.php'>M'inscrire !</a>
      <br/><a href='#sommaire'>Retour au sommaire</a></p>
      <p id='RUQ3'>Que peut faire un joueur et comment le devenir ?<br/>
      Sur <a href='../CadreStatique/index1.php'>Meerketball</a>, le rôle de joueur est le plus commun.
      <br/><a href='#sommaire'>Retour au sommaire</a></p>
      <p id='RUQ4'>Que peut faire un capitaine d'équipe et comment le devenir ?<br/>Réponse à la question 3<br/><a href='#sommaire'>Retour au sommaire</a></p>
      <p id='RUQ5'>Que peut faire un gestionnaire et comment le devenir ?<br/>Réponse à la question 4<br/><a href='#sommaire'>Retour au sommaire</a></p>
      <p id='RUQ6'>Que peut faire un administrateur et comment le devenir ?<br/>Réponse à la question 5<br/><a href='#sommaire'>Retour au sommaire</a></p>
    </div>

    <div id='OTrep'><strong>Organisation de tournois</strong>
      <p id='OTQ1'>Organisation de tournois - Question 1<br/>Réponse à la question 1<br/><a href='#sommaire'>Retour au sommaire</a></p>
      <p id='OTQ2'>Organisation de tournois - Question 2<br/>Réponse à la question 2<br/><a href='#sommaire'>Retour au sommaire</a></p>
      <p id='OTQ3'>Organisation de tournois - Question 3<br/>Réponse à la question 3<br/><a href='#sommaire'>Retour au sommaire</a></p>
    </div>

    <div id='CErep'><strong>Constitution d'Équipe</strong>
      <p id='CEQ1'>Constitution d'Équipe - Question 1<br/>Réponse à la question 1<br/><a href='#sommaire'>Retour au sommaire</a></p>
      <p id='CEQ2'>Constitution d'Équipe - Question 2<br/>Réponse à la question 2<br/><a href='#sommaire'>Retour au sommaire</a></p>
      <p id='CEQ3'>Constitution d'Équipe - Question 3<br/>Réponse à la question 3<br/><a href='#sommaire'>Retour au sommaire</a></p>
    </div>
  <?php
    include("../CadreStatique/skel2.php");
  ?>
</html>