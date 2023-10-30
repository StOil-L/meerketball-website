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

$titreOnglet = "Préinscription aux tournois";
$linkPage = "../Formulaires/Preinscription.php";
$titrePage = "Préinscription aux tournois";
?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");


if ((isset($_POST['Tournois']))) {
  $_SESSION['historique']['idLogin'];
} else if ((isset($_POST['depresincription']))) {
  $_SESSION['historique']['idLogin'];
} else {
  if (isset($_SESSION['historique']['idLogin'])) {
    $_SESSION['historique']['idLogin'];
  } else {
    $_SESSION['historique']['idLogin'] = null;
  }
}


$idshortcut = $_SESSION['historique']['idLogin'];


//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////
//////////\\\\\\\\\\//////////\\\\\\\\\\//////////   Verification Utilisateur   \\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\
//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////

$sql = "SELECT U.IdUtilisateur, U.RoleU, LJ.Capitaine
          FROM Utilisateur U 
          LEFT JOIN ListeJoueur LJ
          ON U.Joueur = LJ.IdJoueur
          WHERE U.RoleU Like 'Administrateur'
          OR U.RoleU Like 'Gestionnaire'
          OR LJ.Capitaine = 1";

//recupere la liste des id utilisateurs
$stmt = $bdd->prepare($sql);
$result = $stmt->execute();
$user = $stmt->fetchALL(PDO::FETCH_OBJ);

/*
  //Affiche la liste des utilisateurs autorisés
  ?>
  <ul>
    <?php foreach ($user as $U) : ?>
      <li><?= $U->IdUtilisateur ?> - <?= $U->RoleU ?> </li>
    <?php endforeach ?>
  </ul>
  <?php 
  */

// test si l'utilisateur est dans la liste et stocke la ligne
$ids = null;
foreach ($user as $us) {
  if ($idshortcut == $us->IdUtilisateur) {
    $ids = $us;
    break;
  }
}

//print_r ($ids);
if (!$ids) {
  echo "Vous ne disposez pas des accréditations necessaire pour procéder à 
      l'enregistrement dans un tournoi, veuillez contacter votre gestionnaire 
      de tournoi ou votre capitaine" . "<br>";
} else {

  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////        Requete Si OK         \\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////

  $role = $ids->RoleU;
  $capitan = $ids->Capitaine;
  //echo $role.$capitan;

?>
  <h1>Selectionnez un tournoi et une équipe</h1>
  <?php  // filtrer les tournois en phase d'inscription seulement
  if (($role == 'Gestionnaire') and !$capitan) {
    $sql2 = " SELECT IdTournoi, Nom, Categorie,TypeT 
                FROM Tournoi
                WHERE IdGestionnaire = $idshortcut
                AND EtatTournoi LIKE 'Phase inscription'";
  } else {
    $sql2 = " SELECT IdTournoi, Nom, Categorie,TypeT  
                FROM Tournoi
                WHERE EtatTournoi LIKE 'Phase inscription'";
  }

  if ($capitan and !($role == 'Administrateur' or $role == 'Gestionnaire')) {
    $sql3 = " SELECT E.IdEquipe, E.Nom, E.Categorie 
                FROM Equipe E, ListeJoueur LJ, Utilisateur U
                WHERE U.IdUtilisateur = $idshortcut
                AND U.Joueur = LJ.IdJoueur
                AND LJ.Capitaine = 1
                AND LJ.IdEquipe = E.IdEquipe
                AND Niveau >= 0";
  } else {
    $sql3 = " SELECT IdEquipe, Nom, Categorie 
                FROM Equipe
                WHERE Niveau >= 0";
  }

  $stmt2 = $bdd->prepare($sql2);
  $stmt2->execute();
  $tourn = $stmt2->fetchALL(PDO::FETCH_OBJ);

  $stmt3 = $bdd->prepare($sql3);
  $stmt3->execute();
  $equip = $stmt3->fetchALL(PDO::FETCH_OBJ);



  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////
  //////////\\\\\\\\\\//////////\\\\\\\\\\////////// Stockage noms sans rerequeter \\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\////////// 

  $tabEquip;
  $inc5 = 0;
  $tabTourn;
  $inc6 = 0;
  foreach ($tourn as $TE5) {
    $tabTourn[$inc5][0] = $TE5->Nom;
    $tabTourn[$inc5][1] = $TE5->IdTournoi;
    $tabTourn[$inc5][2] = $TE5->Categorie;
    $inc5++;
  }
  foreach ($equip as $TE6) {
    $tabEquip[$inc6][0] = $TE6->Nom;
    $tabEquip[$inc6][1] = $TE6->IdEquipe;
    $tabEquip[$inc6][2] = $TE6->Categorie;
    $inc6++;
  }
  // echo"<br>";
  // echo $tabEquip[3][0]; echo $tabEquip[3][1]; echo $tabEquip[3][2];
  // echo $tabTourn[3][0]; echo $tabEquip[3][1]; echo $tabTourn[3][2];
  // echo"<br>";
  // foreach($tabEquip as $tabEq){echo $tabEq[2];}
  // foreach($tabEquip as $tabEq){echo $tabEq[1];}
  // foreach($tabEquip as $tabEq){echo $tabEq[0];}

  if (!isset($_SESSION['historique']['idEquip']) and !isset($_SESSION['historique']['idTourn'])) {
    $_SESSION['historique']['idEquip'] = null;
    $_SESSION['historique']['idTourn'] = null;
    $_SESSION['historique']['nomEquip'] = null;
    $_SESSION['historique']['nomTourn'] = null;
    $_SESSION['historique']['categTou'] = null;
    $_SESSION['historique']['categEq'] = null;
  }

  if ((isset($_POST['Tournois']) and isset($_POST['Equipes'])) or (isset($_POST['tournoisD']) and isset($_POST['equipesD']))) {
    if (isset($_POST['Tournois']) and isset($_POST['Equipes'])) {
      foreach ($tabEquip as $tabEq) {
        if ($tabEq[1] == $_POST['Equipes']) {
          $_SESSION['historique']['idEquip'] = $tabEq[1];
          $_SESSION['historique']['nomEquip'] = $tabEq[0];
          $_SESSION['historique']['categEq'] = $tabEq[2];
        }
      }
      foreach ($tabTourn as $tabTou) {
        if ($tabTou[1] == $_POST['Tournois']) {
          $_SESSION['historique']['idTourn'] = $tabTou[1];
          $_SESSION['historique']['nomTourn'] = $tabTou[0];
          $_SESSION['historique']['categTou'] = $tabTou[2];
        }
      }
      $idChoixTour = $_POST['Tournois'];
    } else if (isset($_POST['tournoisD']) and isset($_POST['equipesD'])) {
      foreach ($tabEquip as $tabEq) {
        if ($tabEq[1] == $_POST['equipesD']) {
          $_SESSION['historique']['idEquip'] = $tabEq[1];
          $_SESSION['historique']['nomEquip'] = $tabEq[0];
          $_SESSION['historique']['categEq'] = $tabEq[2];
        }
      }
      foreach ($tabTourn as $tabTou) {
        if ($tabTou[1] == $_POST['tournoisD']) {
          $_SESSION['historique']['idTourn'] = $tabTou[1];
          $_SESSION['historique']['nomTourn'] = $tabTou[0];
          $_SESSION['historique']['categTou'] = $tabTou[2];
        }
      }
    }
  }
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////          Formulaire          \\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////
?>
  <form action="#" method="post">
    <fieldset>
      <!--div class="form-group"-->

      <select name="Tournois" id="Tournois"><label for="Tournois"></label></br>
        <option label="Tournoi" DISABLED SELECTED> </option>
        <?php if (isset($_POST['Tournois']) or isset($_POST['tournoisD'])) {
          if (isset($_POST['conserver']) and $_POST['conserver'] == "consTou") {
        ?>
            <option value="<?= $_SESSION['historique']['idTourn'] ?>" id="<?= $_SESSION['historique']['idTourn'] ?>" for="<?= $_SESSION['historique']['idTourn'] ?>" SELECTED><?= $_SESSION['historique']['nomTourn'] . " - [" . $_SESSION['historique']['categTou'] . "]"  ?></label></option>
          <?php
          } else {
          ?>
            <option label=" Choix précédent : <?= $_SESSION['historique']['nomTourn'] ?> : <?= $_SESSION['historique']['categTou'] ?> " DISABLED SELECTED></option>
          <?php
          }
        }
        foreach ($tourn as $TE) : ?>
        <?php $typeTournoi=$TE->TypeT; 
        if($typeTournoi=='Coupe'){
          $typeTournoi='Tournoi à élimination directe';
        }
        else if($test=='PlusieursTours'){
        $typeTournoi='Tournoi aller-retour';  
        }
        ?>
          <option value="<?= $TE->IdTournoi ?>" id="<?= $TE->IdTournoi ?>"><label for="<?=   $TE->IdTournoi ?>"><?=  $TE->Nom . " - [" . "$typeTournoi : ". $TE->Categorie . "]"  ?></label></option>
        <?php endforeach ?>
      </select>

      <select name="Equipes" id="Equipes"><label for="Equipes"></label></br>
        <option label="Équipe" DISABLED SELECTED> </option>
        <?php if (isset($_POST['Equipes']) or isset($_POST['equipesD'])) {
          if (isset($_POST['conserver']) and $_POST['conserver'] == "consEqu") {
        ?>
            <option value="<?= $_SESSION['historique']['idEquip'] ?>" id="<?= $_SESSION['historique']['idEquip'] ?>" for="<?= $_SESSION['historique']['idEquip'] ?>" SELECTED><?= $_SESSION['historique']['nomEquip'] . " - [" . $_SESSION['historique']['categEq'] . "]"  ?></label></option>
          <?php
          } else {
          ?>
            <option label=" Choix précédent : <?= $_SESSION['historique']['nomEquip'] ?> : <?= $_SESSION['historique']['categEq'] ?> " DISABLED SELECTED></option>
          <?php
          }
        }
        foreach ($equip as $TE2) : ?>
          <option value="<?= $TE2->IdEquipe ?>" id="<?= $TE2->IdEquipe ?>"><label for="<?= $TE2->IdEquipe ?>"><?= $TE2->Nom . " - [" . $TE2->Categorie . "]"  ?></label></option>
        <?php endforeach ?>
      </select>

      <br><br>
      <div class='info'> Enregistrer un des deux choix pour faire plusieurs inscriptions de la même équipe à plusieurs tournois, ou de plusieurs équipes dans un même tournoi :
        <?php if (isset($_POST['conserver']) and $_POST['conserver'] == "consTou") {
        ?>
          <input type="radio" id="consEqu" name="conserver" value="consEqu"><label for="consEqu">Équipe</label>
          <input type="radio" id="consTou" name="conserver" value="consTou" checked><label for="consTou" >Tournoi</label>
          <input type="radio" id="consAuc" name="conserver" value="consAuc"><label for="consAuc">Aucun</label>
        <?php } else if (isset($_POST['conserver']) and $_POST['conserver'] == "consEqu") {
        ?>
          <input type="radio" id="consEqu" name="conserver" value="consEqu" checked><label for="consEqu" >Équipe</label>
          <input type="radio" id="consTou" name="conserver" value="consTou"><label for="consTou">Tournoi</label>
          <input type="radio" id="consAuc" name="conserver" value="consAuc"><label for="consAuc">Aucun</label>
        <?php } else {
        ?>
          <input type="radio" id="consEqu" name="conserver" value="consEqu"><label for="consEqu">Équipe</label>
          <input type="radio" id="consTou" name="conserver" value="consTou"><label for="consTou">Tournoi</label>
          <input type="radio" id="consAuc" name="conserver" value="consAuc" checked><label for="consAuc">Aucun</label>
        <?php } ?>
      </div>
      <!--/div-->
      <br>
      <button class="click-ligne">Valider</button>

    </fieldset>
  </form>
  <?php
  //echo date_format($temps, "Y/m/d H:i:s");

  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////          Affichage           \\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////

  //Affichage
  /*
    ?>
    <ul>
      <?php foreach ($tourn as $TE) : ?>
        <li><?= $TE->Nom ?></li>
      <?php endforeach ?>
    </ul>
    <ul>
      <?php foreach ($equip as $TE2) : ?>
        <li><?= $TE2->Nom ?></li>
      <?php endforeach ?>
    </ul>
      
    <?php 
    */


  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////        Désinscription        \\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////





  if (isset($_POST['depresincription'])) {
    // echo "<br>";
    // echo "IdEquipe selectionnée : " . ($_POST['equipesD']) . "<br>";
    // echo "IdTournoi selectionné : " . $_POST['tournoisD'] . "<br>";
    // echo "<br>";

    $existCorrespGestion = 1;
    $existCorrespCapitan = 1;
    if ((($capitan == 1) and ($role == 'Gestionnaire'))) {
      // si c'est le tournois du gestionnaire il peut desinscrire toutes les equipes
      // si c'est le capitaine de 'lequipe, il peut desinscrire l'equipe de tous les tournois

      // on va chercher s'il existe une entrée, peu importe l'attribut
      $chercheCorrespGestion = $bdd->prepare("  SELECT *   
                                                        FROM Tournoi
                                                        WHERE  IdTournoi = :IdTou
                                                        AND IdGestionnaire = $idshortcut");
      $chercheCorrespGestion->execute([
        'IdTou' => $_POST['tournoisD']
      ]);
      $existCorrespGestion = $chercheCorrespGestion->fetch(PDO::FETCH_OBJ);

      if (empty($existCorrespGestion)) {
        // on va chercher s'il existe une entrée, peu importe l'attribut
        $chercheCorrespCapitan = $bdd->prepare("  SELECT *
                                                    FROM Utilisateur U, ListeJoueur LJ
                                                    WHERE LJ.Capitaine = 1
                                                    AND LJ.IdEquipe = :IdEq
                                                    AND U.IdUtilisateur = $idshortcut
                                                    AND LJ.IdJoueur = U.Joueur");
        $chercheCorrespCapitan->execute([
          'IdEq' => $_POST['equipesD']
        ]);
        $existCorrespCapitan = $chercheCorrespCapitan->fetch(PDO::FETCH_OBJ);
      }
    }
    if (empty($existCorrespGestion) and empty($existCorrespCapitan)) { // gestionnaire d'un autre tournoi essayant de desinscrire sans le statut de capitaine
      $fail2 = "Désinscription non autorisée, vous n'êtes pas le gestionnaire de ce tournoi et ne pouvez pas désinscrire une équipe dont vous n'êtes pas capitaine";

  ?>
      <div class="alert alert-danger"><?= $fail2 ?></div>
    <?php

    } else {
      $désinscription = $bdd->prepare(" DELETE 
                                          FROM ListeEquipe 
                                          WHERE IdEquipe = :IdEq
                                          AND IdTournoi = :IdTou
                                          AND AttenteInscription = 1");
      $désinscription->execute([
        'IdTou' => $_POST['tournoisD'],
        'IdEq' => $_POST['equipesD'],
      ]);

      $success2 = "Désinscription effectuée";
    ?>
      <div class="alert alert-success"><?= $success2 ?></div>
      <?php
    }
  }

  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////         Confirmation         \\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\
  //////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////\\\\\\\\\\//////////

  if (!empty($_POST['Tournois']) and !empty($_POST['Equipes'])) {
    //print_r($_POST);
    $success = null;
    if (
      $role == 'Administrateur' or $role == 'Gestionnaire' or $capitan
    ) {
      $stmt4 = $bdd->prepare('SELECT AttenteInscription FROM ListeEquipe WHERE IdTournoi = :IdTournoi AND IdEquipe = :IdEquipe');
      $stmt4->execute([
        'IdTournoi' => $_POST['Tournois'],
        'IdEquipe'  => $_POST['Equipes']
      ]);
      $preinsOK = $stmt4->fetch(PDO::FETCH_OBJ);
      //print_r($preinsOK);  //affiche l'état d'attente inscription
      if (!$preinsOK) {  // si null
        $existCorrespGestionInsc = 1;
        $existCorrespCapitanInsc = 1;
        if ((($capitan == 1) and ($role == 'Gestionnaire'))) {
          // si c'est le tournois du gestionnaire il peut inscrire toutes les equipes
          // si c'est le capitaine de l'equipe, il peut inscrire l'equipe à tous les tournois

          // on va chercher s'il existe une entrée, peu importe l'attribut
          $chercheCorrespGestionInsc = $bdd->prepare("  SELECT *   
                                                          FROM Tournoi
                                                          WHERE  IdTournoi = :IdTou
                                                          AND IdGestionnaire = $idshortcut");
          $chercheCorrespGestionInsc->execute([
            'IdTou' => $_POST['Tournois']
          ]);
          $existCorrespGestionInsc = $chercheCorrespGestionInsc->fetch(PDO::FETCH_OBJ);
          if (empty($existCorrespGestionInsc)) {

            // on va chercher s'il existe une entrée, peu importe l'attribut
            $chercheCorrespCapitanInsc = $bdd->prepare("  SELECT *
                                                      FROM Utilisateur U, ListeJoueur LJ
                                                      WHERE LJ.Capitaine = 1
                                                      AND LJ.IdEquipe = :IdEq
                                                      AND U.IdUtilisateur = $idshortcut
                                                      AND LJ.IdJoueur = U.Joueur");
            $chercheCorrespCapitanInsc->execute([
              'IdEq' => $_POST['Equipes']
            ]);
            $existCorrespCapitanInsc = $chercheCorrespCapitanInsc->fetch(PDO::FETCH_OBJ);
          }
        }
        if (empty($existCorrespGestionInsc) and empty($existCorrespCapitanInsc)) { // gestionnaire d'un autre tournoi essayant de desinscrire sans le statut de capitaine
          echo "<br>";
          $fail1 = "Inscription non autorisée, vous n'êtes pas le gestionnaire de ce tournoi et ne pouvez pas inscrire une équipe dont vous n'êtes pas capitaine";
          echo "<br>";
      ?>
          <div class="alert alert-danger"><?= $fail1 ?></div>
          <?php

        } else {
          // test de comptabilité des catégories
          $idTourno = $_POST['Tournois'];
          $idEquip = $_POST['Equipes'];

          $sql42 = "SELECT Categorie
          FROM Equipe
          WHERE IdEquipe = $idEquip";
          $sql43 = "SELECT Categorie
          FROM Tournoi
          WHERE IdTournoi = $idTourno";
          $stmt42 = $bdd->query($sql42);
          $stmt43 = $bdd->query($sql43);

          $id42 = $stmt42->fetch(PDO::FETCH_ASSOC);
          $id43 = $stmt43->fetch(PDO::FETCH_ASSOC);

          if ($id42['Categorie']==$id43['Categorie']) {
            $preinscription = $bdd->prepare('INSERT INTO ListeEquipe (IdEquipe, IdTournoi, AttenteInscription, DateInscription) VALUES(:IdEquipe, :IdTournoi, 1, :datedemande)');
            $preinscription->execute([
              'IdTournoi' => $_POST['Tournois'],
              'IdEquipe' => $_POST['Equipes'],
              'datedemande' => date('Y-m-d H:i:s')
            ]);
            $success = "L'inscription a été prise en compte !";
            if ($success) {
          ?>
              <div class="alert alert-success"><?= $success ?></div>
            <?php
            }
          } else {
            $fail3 = "Inscription non autorisée, l'équipe et le tournoi sélectionnés appartiennent à des catégories différentes";
            echo "<br>";
            ?>
            <div class="alert alert-danger"><?= $fail3 ?></div>
        <?php
          }
        }
      } else if ($preinsOK->AttenteInscription == 1) { // si = 1

        ?><div class="alert alert-danger"><?= "<br>" . "Erreur lors de la validation, la demande est déjà en cours de traitement" . "<br>"; ?></div><?php

                                                                                                                                                    echo "Voulez-vous annuler la demande d'inscription ? Appuyez sur \"Se désinscrire\"";
                                                                                                                                                    ?>
        <form method="post" action="#">
          <input type="hidden" value="<?= $_POST['Tournois'] ?>" id="tournoisD" name="tournoisD">
          <input type="hidden" value="<?= $_POST['Equipes'] ?>" id="equipesD" name="equipesD">
          <button class="click" name="depresincription" id="depresincription" value="depresincription">Se désinscrire</button>
        </form>
      <?php

      } else { // si = 0
      ?><div class="alert alert-danger"><?= "<br>" . "Erreur lors de la validation, l'équipe est déjà inscrite à ce tournoi." . "<br>"; ?></div><?php

                                                                                                                                              }
                                                                                                                                            } else {
                                                                                                                                                ?><div class="alert alert-danger"><?= "<br>" . "Erreur lors de la validation, vous n'avez pas l'accreditation necessaire pour préinscrire ce choix" . "<br>"; ?></div><?php

                                                                                                                                                                                                                                                                                                                          }
                                                                                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                                                                                      }
                                                                                                                                                                                                                                                                                                                      include("../CadreStatique/skel2.php");
                                                                                                                                                                                                                                                                                                                            ?>

</html>
