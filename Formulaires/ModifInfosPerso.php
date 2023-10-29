<?php
session_start();
include("connexion.php");
date_default_timezone_set('Europe/Paris');

try {
  $dbh = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
  echo $e->getMessage();
  die("connexion Impossible !");
}

$titreOnglet = "Modifications";
$linkPage = "";
$titrePage = "Modifications";

?>
<!doctype html>
<?php
include("../CadreStatique/skel1.php");
if(isset($_SESSION['historique']['idLogin']))
{
  echo "<h1>  Compte Utilisateur</h1>";
  $ID = $_SESSION['historique']['idLogin'];
  $sql = "SELECT LoginU, EMail, DatesInscription, Nom, Prenom, RoleU, OptionDalt FROM Utilisateur WHERE IdUtilisateur=$ID";
  $result = $dbh->query($sql);

  echo "<h2>Informations personnelles de ". $_SESSION['historique']['login'] ."</h2>";
  if ($row = $result->fetch()) // S'il y a un ou des résultat.s
  {
    do {
      echo "<form action='ModifInfosPerso.php' method='post'>";

      if ($row['LoginU'] !== NULL)
      {
        echo "Identifiant : " . "<input type=text name=Identifiant placeholder=" . $row['LoginU'] . " />";
      }
      else
      {
        echo " - ";
      }
      echo "<br/>";

      if ($row['Prenom'] !== NULL)
      {
        echo "Prénom : " . "<input type=text name=Prenom placeholder=" . $row['Prenom'] . " />";
      }
      else
      {
        echo " - ";
      }
      echo "<br/>";

      if ($row['Nom'] !== NULL)
      {
        echo "Nom : " . "<input type=text name=Nom placeholder=" . $row['Nom'] . " />";
      }
      else
      {
        echo " - ";
      }
      echo "<br/>";

      if ($row['EMail'] !== NULL)
      {
        echo "Adresse e-mail : " . "<input type=email name=EMail placeholder=" . $row['EMail'] . " />";
      }
      else
      {
        echo " - ";
      }
      echo "<br/><br/>";

      if ($row['RoleU'] !== NULL)
      {
        echo "Rôle :  " . $row['RoleU'];
      }
      else
      {
        echo " - ";
      }
      echo "<br/>";

      echo "<br/><br/>";
      echo "Modifier le mot de passe :<br/>";
      echo "Mot de passe actuel : " . "<input type=password name=Amdp /><br/>";
      echo "Nouveau mot de passe : " . "<input type=password name=Nmdp />";
      echo "<br/><br/><br/>";


      /*if ($row['OptionDalt'] == 1)
      {
        echo "Option daltonisme : Activée";
      }
      else
      {
        echo "Option daltonisme : Désactivée";
      }
      echo "<br/>";*/
      


      if ($row['OptionDalt'] == 1) {
        $daltOn = 1;
      ?>
        <div class='info'>Mode daltonien : 
        <input type="radio" name="daltonien" value="on" checked> Activé </input>
        <input type="radio" name="daltonien" value="off"> Désactivé </input>
        </div>
      <?php
      } else {
        $daltOn = 0;
      ?>
        <div class='info'>Mode daltonien : 
        <input type="radio" name="daltonien" value="on"> Activé </input>
        <input type="radio" name="daltonien" value="off" checked> Désactivé </input>
        </div>
      <?php
      }

      if ($row['DatesInscription'] !== NULL)
      {
        echo "Comtpe existant depuis " . $row['DatesInscription'];
      }
      else
      {
        echo " - ";
      }
      echo "<br/>";

      echo "<input type='submit' class='click' value='Valider les modifications'></form>";

    } while ($row = $result->fetch());
  }

  echo "<br/>";



  if(isset($_POST['Identifiant']) && ($_POST['Identifiant']) !== '')
  {
    $Postid=$_POST['Identifiant'];
    $sqlcheck = "SELECT COUNT(*) as compte FROM Utilisateur WHERE LoginU='$Postid'";
    $check = $dbh->prepare($sqlcheck);
    $check->execute();
    $rowcheck = $check->fetch(PDO::FETCH_ASSOC);
    $nombre = $rowcheck['compte'];

    if ($nombre > 0)
    {
      echo "<div class='alert alert-danger'>Cet identifiant n'est pas disponible</div><br/>";
    }
    else
    {
      $dbh->exec("UPDATE Utilisateur SET LoginU='$Postid' WHERE IdUtilisateur=$ID");

      $sql = "SELECT LoginU FROM Utilisateur WHERE IdUtilisateur=$ID";
      $result = $dbh->query($sql);


      if ($row = $result->fetch())
      {
        do
        {
          if ($row['LoginU'] == $_POST['Identifiant'])
          {
            $_SESSION['historique']['login'] = $Postid;
            echo "<div class='alert alert-success'>Identifiant modifié</div><br/>";
            ?>
            <script>
              window.location = "../Formulaires/ModifInfosPerso.php"
            </script>
            <?php
          }
          else
          {
            echo "<div class='alert alert-danger'>Erreur lors de la modification de l'identifiant</div><br/>";
          }
        } while ($row = $result->fetch());
      }
    }
  }

  if(isset($_POST['Prenom']) && ($_POST['Prenom']) !== '')
    {
    $Postprenom=$_POST['Prenom'];
      
    $dbh->exec("UPDATE Utilisateur SET Prenom='$Postprenom' WHERE IdUtilisateur=$ID");

    $sql = "SELECT Prenom FROM Utilisateur WHERE IdUtilisateur=$ID";
    $result = $dbh->query($sql);

    if ($row = $result->fetch())
    {
      do
      {
        if ($row['Prenom'] == $_POST['Prenom'])
        {
          echo "<div class='alert alert-success'>Prénom modifié</div><br/>";
          ?>
          <script>
            window.location = "../Formulaires/ModifInfosPerso.php"
          </script>
          <?php
        }
        else
        {
          echo "<div class='alert alert-danger'>Erreur lors de la modification du prénom</div><br/>";
        }
      } while ($row = $result->fetch());
    }
  }



  if(isset($_POST['Nom']) && ($_POST['Nom']) !== '')
  {
    $Postnom=$_POST['Nom'];
      
    $dbh->exec("UPDATE Utilisateur SET Nom='$Postnom' WHERE IdUtilisateur=$ID");

    $sql = "SELECT Nom FROM Utilisateur WHERE IdUtilisateur=$ID";
    $result = $dbh->query($sql);

    if ($row = $result->fetch())
    {
      do
      {
        if ($row['Nom'] == $_POST['Nom'])
        {
          echo "<div class='alert alert-success'>Nom modifié</div><br/>";
          ?>
          <script>
            window.location = "../Formulaires/ModifInfosPerso.php"
          </script>
          <?php
        }
        else
        {
          echo "<div class='alert alert-danger'>Erreur lors de la modification du nom</div><br/>";
        }
      } while ($row = $result->fetch());
    }
  }

  if(isset($_POST['Nmdp']) && ($_POST['Nmdp']) !== '')
  {
    $PostNmdp=$_POST['Nmdp'];
    
    if(isset($_POST['Amdp']) && ($_POST['Amdp']) !== '')
    {
      $PostAmdp=$_POST['Amdp'];

      $sqlpass = "SELECT MotDePasse FROM Utilisateur WHERE IdUtilisateur=$ID";
      $resultpass = $dbh->query($sqlpass);

      if ($rowpass = $resultpass->fetch())
      {
        do
        {
          if (password_verify($PostAmdp , $rowpass['MotDePasse']))
          {
            $Nmdphash = password_hash($PostNmdp, PASSWORD_DEFAULT);
            $dbh->exec("UPDATE Utilisateur SET MotDePasse='$Nmdphash' WHERE IdUtilisateur=$ID");
            echo "<div class='alert alert-success'>Mot de passe modifié</div><br/>";
          }
          else
          {
            echo "<div class='alert alert-danger'>Mot de passe actuel incorrect</div><br/>";
          }
        } while ($rowpass = $resultpass->fetch());
      }
    }
    else
    {
      echo "<div class='alert alert-danger'>Veuillez renseigner votre mot de passe actuel</div><br/>";
    }
  }

  if (isset($_POST['daltonien'])) {
    if (($_POST['daltonien']) == 'on') {
      $Postdalt = 1;
    } else {
      $Postdalt = 0;
    }
    if ($daltOn != $Postdalt) { 
      $dbh->exec("UPDATE Utilisateur SET OptionDalt=$Postdalt WHERE IdUtilisateur=$ID");
      $_SESSION['historique']['dalt'] = $Postdalt;
      ?>
      <script>
        window.location = "../Formulaires/ModifInfosPerso.php"
      </script>
      <?php
    }
  }

/*
  if(isset($_POST['daltonien']) && ($_POST['daltonien']) !== '')
  {
    if (($_POST['daltonien']) == 'on')
    {
      $Postdalt = '1';
    }
    else
    {
      $Postdalt = '0';
    }

    $sql = "SELECT OptionDalt FROM Utilisateur WHERE IdUtilisateur=$ID";
    $result = $dbh->query($sql);

    if ($row = $result->fetch())
    {
      do
      {
        if ($row['OptionDalt'] != $Postdalt)
        {
          $dbh->exec("UPDATE Utilisateur SET OptionDalt=$Postdalt WHERE IdUtilisateur=$ID");
          echo "<div class='alert alert-success'>Option daltonisme modifiée</div><br/>";
        }
      } while ($row = $result->fetch());
    }
  }
  */
  
  
  if(isset($_POST['EMail']) && ($_POST['EMail']) !== '')
  {
  $Postmail=$_POST['EMail'];
    
  $dbh->exec("UPDATE Utilisateur SET EMail='$Postmail' WHERE IdUtilisateur=$ID");

  $sql = "SELECT EMail FROM Utilisateur WHERE IdUtilisateur=$ID";
  $result = $dbh->query($sql);

  if ($row = $result->fetch())
  {
    do
    {
      if ($row['EMail'] == $_POST['EMail'])
      {
        echo "<div class='alert alert-success'>Adresse e-mail modifié</div><br/>";
        ?>
        <script>
          window.location = "../Formulaires/ModifInfosPerso.php"
        </script>
        <?php
      }
      else
      {
        echo "<div class='alert alert-danger'>Erreur lors de la modification de l'adresse e-mail</div><br/>";
      }
    } while ($row = $result->fetch());
  }
}



}
else
{
  echo "<br/><div class='alert alert-danger'>Vous devez être un utilisateur isncrit pour venir ici</div>"; 
}


?>

<?php
  include("../CadreStatique/skel2.php");
?>
</html>