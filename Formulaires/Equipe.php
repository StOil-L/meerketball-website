<?php
session_start();
include("../CadreStatique/connexion.php");
date_default_timezone_set('Europe/Paris');

try{
    $dbh=new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8",$user,$pass,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    }
    
    catch(PDOException $e){
    echo $e->getMessage();
    die("Connexion impossible.");
    }

$titreOnglet="";
$linkPage="";
$titrePage="";

?>
<!doctype html>
                    <?php
                    include("../CadreStatique/skel1.php");
                    ?>

<h1>Creation d'Equipe</h1>


<?php

if(isset($_SESSION['historique']['login'])){


if(!isset($_POST['categorie'])){
if(!isset($_POST['joueurscree'])){
if(!isset($_POST['create'])){
if(!isset($_POST['creer'])){
echo "<form  method=post action=Equipe.php >
Souhaitez vous créer une équipe ?
<button type=submit class='click-ligne' name=creer> Créer une équipe </button>
</form>";
}
}
}
}

if(isset($_POST['creer']) OR isset($_POST['categorie']) AND $_POST['categorie']==''){


echo"    
    <form method=post action=Equipe.php>
    <select name='categorie' >
    <option value=''>--choisissez une catégorie--</option>
    <option value='Poussin'>Poussin</option>
    <option value='Benjamin'>Benjamin</option>
    <option value='Junior'>Junior</option>
    <option value='Senior'>Senior</option>
    <option value='Handicap'>Handicap</option>
    <option value='FeminineSenior'>Féminine Senior</option>
</select>
<button type=submit class='click' name=create> Valider </button>

</form>";
}
if(isset($_POST['categorie']) AND $_POST['categorie']!=''){
    $_SESSION['historique']['categorie']=$_POST['categorie'];
    
    echo " <form method=post action=Equipe.php enctype=multipart/form-data>
    Les champs ayant un * sont obligatoires.
    </br>
    <br/><br/>
      ----------------------  Nom de l'équipe :  *<input type=text name=nomEquipe required> ---------------------- 
      <br/><br/>
      Votre prénom:  *<input type=text name=prenom1 required> 
      Votre nom:  *<input type=text name=nom1 required>
      Votre ID : *<input type=number min=0 max=999999999 name=idJoueur1 required>    L'ID d'un joueur se trouve dans sa fiche licence. 
      <br/>
      date de naissance : <input type=date name=dateNaissance1 >
      annee inscription : <input type=date name=anneeInscription1 >  
      <br/>
      Prenom joueur 2:  *<input type=text name=prenom2 required>
      nom joueur 2:  *<input type=text name=nom2 required>
      ID Joueur2 : *<input type=number min=0 max=999999999 name=idJoueur2 required>
      <br/>
      date de naissance : <input type=date name=dateNaissance2 pattern=[0-9]{4}-[0-9]{2}-[0-9]{2}>
      annee inscription : <input type=date name=anneeInscription2 pattern=[0-9]{4}-[0-9]{2}-[0-9]{2}>
      <br/>
      Prenom joueur 3:  *<input type=text name=prenom3 required>
      nom joueur 3:  *<input type=text name=nom3 required>
      ID Joueur3 : *<input type=number min=0 max=999999999 name=idJoueur3 required>
      <br/>
      date de naissance : <input type=date name=dateNaissance3 pattern=[0-9]{4}-[0-9]{2}-[0-9]{2}>
      annee inscription : <input type=date name=anneeInscription3 pattern=[0-9]{4}-[0-9]{2}-[0-9]{2}>
      <br/>
      Prenom joueur 4:  *<input type=text name=prenom4 required>
      nom joueur 4:  *<input type=text name=nom4 required>
      ID Joueur4 : *<input type=number min=0 max=999999999 name=idJoueur4 required>  
      <br/>   
      date de naissance : <input type=date name=dateNaissance4 pattern=[0-9]{4}-[0-9]{2}-[0-9]{2}>
      annee inscription : <input type=date name=anneeInscription4 pattern=[0-9]{4}-[0-9]{2}-[0-9]{2}>
      <br/>
      Prenom joueur 5:  *<input type=text name=prenom5 required>
      nom joueur 5:  *<input type=text name=nom5 required>
      ID Joueur5 : *<input type=number min=0 max=999999999 name=idJoueur5 required>
      <br/>
      date de naissance : <input type=date name=dateNaissance5 pattern=[0-9]{4}-[0-9]{2}-[0-9]{2}>
      annee inscription : <input type=date name=anneeInscription5 pattern=[0-9]{4}-[0-9]{2}-[0-9]{2}>
      <br/><br/>
      Quel est le niveau(ELO) de votre équipe ? : *<input type=number min=0 max=100 name=elo required>
      <br/><br/>
      Veuillez saisir un mail : *<input type=mail name=mail pattern=[^@]+@[^@]+\.[a-zA-Z]{2,6}  required>
      <br/><br/>
      Veuillez saisir un numéro de télephone ? : *<input type=tel minlength=10  maxlength=10 pattern=[0-9]*
      name=telephone required>
      <br/><br/>
	  Veuillez envoyer une image pour l'équipe : <input type='file' id='imageEquipeID' name='imageEq' accept='image/png, image/jpeg'>
	  <br/><br/>
      <button type='submit' class='click' name='joueurscree'> Valider </button>
</form>";

}

$except = 0;
if(isset($_POST['joueurscree']) && !empty($_FILES["imageEq"]['name'])) {
	$fichier_dest = "../ImagesUtilisateur/Equipes/".basename($_FILES["imageEq"]["name"]);
	$envoi = 1;
	$verif = getimagesize($_FILES["imageEq"]["tmp_name"]);
	if($verif == false) {
		$except = 1;
		$envoi = 0;
	}
	if (file_exists($fichier_dest)){
		$except = 2;
		$envoi = 0;
	}
	if ($envoi == 1) move_uploaded_file($_FILES["imageEq"]["tmp_name"], $fichier_dest);
}

if(isset($_POST['joueurscree'])){
	$_SESSION['historique']['imageEq'] = $_FILES["imageEq"]['name'];
	$image = $_SESSION['historique']['imageEq'];
	if(empty($image)){
		$image = "defautEquipe.png";
	}
	
    if($_POST['idJoueur1']==$_POST['idJoueur2']||$_POST['idJoueur1']==$_POST['idJoueur3']||$_POST['idJoueur1']==$_POST['idJoueur4']||$_POST['idJoueur1']==$_POST['idJoueur5']||$_POST['idJoueur2']==$_POST['idJoueur3']||$_POST['idJoueur2']==$_POST['idJoueur4']||$_POST['idJoueur2']==$_POST['idJoueur5']||$_POST['idJoueur3']==$_POST['idJoueur4']||$_POST['idJoueur3']==$_POST['idJoueur5']||$_POST['idJoueur4']==$_POST['idJoueur5']){

        echo "<div class='alert alert-danger'> Erreur </br> Veuillez saisir 5 joueurs différents </div>";
        echo "<meta http-equiv='refresh' content='2;URL=Equipe.php?redirection=Equipe.php'/>";
    }

    else {
        $categorie=$_SESSION['historique']['categorie'];
        
        
        switch($categorie) {
            case 'Poussin':
                $categorie=1;
                break;
            case 'Junior':
                $categorie=2;
                break;
            case 'Senior':
                $categorie=3;
                break;
            case 'Benjamin':
                $categorie=4;
                break;    
            case 'FeminineSenior':
                $categorie=5;
                break;
            case 'Handicap':
                $categorie=6;
                break;
        }

        if($_POST['nom1']==''OR $_POST['nom2']=='' OR $_POST['nom3']=='' OR $_POST['nom4']=='' OR $_POST['nom5']=='' OR $_POST['prenom1']=='' OR $_POST['prenom2']=='' OR $_POST['prenom3']=='' OR $_POST['prenom4']=='' OR $_POST['prenom5']=='' OR $_POST['nomEquipe']==''OR $_POST['elo']==''){

    //Ne sert plus à cause du mot clé required, servira a nouveau pour gérer les exceptions (noms déjà utilisé mais interdit par exemple)

            if($_POST['nomEquipe']==''){
        
                echo "Veuillez saisir un nom d'équipe";
                echo "<br/>";
            }    

            if($_POST['elo']==''){

                echo "Veuillez saisir votre ELO";
                echo "<br/>";
            }


            if($_POST['nom1']==''){

                echo "Veuillez saisir le nom du joueur 1";
                echo "<br/>";
            }

            if($_POST['prenom1']==''){

                echo "Veuillez saisir le prénom du joueur 1";
                echo "<br/>";
            }

            if($_POST['nom2']==''){

                echo "Veuillez saisir le nom du joueur 2";
                echo "<br/>";
            }

            if($_POST['prenom2']==''){

                echo "Veuillez saisir le prénom du joueur 2";
                echo "<br/>";
            }

            if($_POST['nom3']==''){

                echo "Veuillez saisir le nom du joueur 3";
                echo "<br/>";
            }

            if($_POST['prenom3']==''){

                echo "Veuillez saisir le prénom du joueur 3";
                echo "<br/>";
            }

            if($_POST['nom4']==''){

                echo "Veuillez saisir le nom du joueur 4";
                echo "<br/>";
            }

            if($_POST['prenom4']==''){

                echo "Veuillez saisir le prénom du joueur 4";
                echo "<br/>";
            }

            if($_POST['nom5']==''){

                echo "Veuillez saisir le nom du joueur 5";
                echo "<br/>";
            }

            if($_POST['prenom5']==''){

                echo "Veuillez saisir le prénom du joueur 5";
                echo "<br/>";
            }

            echo " <br/>
                <form method=post action=Equipe.php>
                <button type=submit class='click' name=categorie> Retour à la page précédente </button>
                </form>";
        }
            else  if($_POST['nom1']!=''AND $_POST['nom2']!='' AND $_POST['nom3']!='' AND $_POST['nom4']!='' AND $_POST['nom5']!='' AND$_POST['prenom1']!=''AND $_POST['prenom2']!='' AND $_POST['prenom3']!='' AND $_POST['prenom4']!='' AND $_POST['prenom5']!='' AND $_POST['nomEquipe']!=''AND $_POST['elo']!=''){
            

                    if($_POST['dateNaissance1']==''){    
                        $dateN1= '2400-01-01';
                    }
                    else $dateN1="$_POST[dateNaissance1]";

                    if($_POST['dateNaissance2']==''){    
                        $dateN2= '2400-01-01';
                    }
                    else $dateN2=$_POST['dateNaissance2'];

                    if($_POST['dateNaissance3']==''){    
                        $dateN3= '2400-01-01';
                    }
                    else $dateN3=$_POST['dateNaissance3'];

                    if($_POST['dateNaissance4']==''){    
                        $dateN4= '2400-01-01';
                    }
                    else $dateN4=$_POST['dateNaissance4'];

                    if($_POST['dateNaissance5']==''){    
                        $dateN5='2400-01-01';
                    }
                    else $dateN5=$_POST['dateNaissance5'];
                
                    if($_POST['anneeInscription1']==''){
                        $dateI1= '2400-01-01';
                    }
                    else $dateI1=$_POST['anneeInscription1'];

                    if($_POST['anneeInscription2']==''){
                        $dateI2= '2400-01-01';
                    }
                    else $dateI2=$_POST['anneeInscription2'];

                    if($_POST['anneeInscription3']==''){
                        $dateI3= '2400-01-01';
                    }
                    else $dateI3=$_POST['anneeInscription3'];

                    if($_POST['anneeInscription4']==''){
                        $dateI4= '2400-01-01';
                    }
                    else $dateI4=$_POST['anneeInscription4'];

                    if($_POST['anneeInscription5']==''){
                        $dateI5= '2400-01-01';
                }
                    else $dateI5=$_POST['anneeInscription5'];
            

                $dbh->exec("INSERT INTO Equipe(Categorie,Nom,Niveau,Mail,Telephone,Images) VALUES($categorie,'$_POST[nomEquipe]','$_POST[elo]','$_POST[mail]','$_POST[telephone]','".$image."')");
                $sql="SELECT idEquipe FROM Equipe WHERE Equipe.Nom='$_POST[nomEquipe]' AND idEquipe>=ALL(SELECT idEquipe FROM Equipe) ";
                $identiteEquipe='';
                foreach($dbh->query($sql) as $row){
                        $identiteEquipe=$row['idEquipe'];
                    
            }
            $idExiste=0;
            $sql="SELECT idJoueur FROM Joueur";
            foreach($dbh->query($sql) as $row){
                    if($_POST['idJoueur1']==$row['idJoueur']){
                            $idExiste=1;
                    }
            }
                if($idExiste==0){
                    $dbh->exec("INSERT INTO Joueur(IdJoueur,Nom,Prenom,DateNaissance,Categorie,AnneeInscription) VALUES('$_POST[idJoueur1]','$_POST[nom1]','$_POST[prenom1]','$dateN1',$categorie,'$dateI1')");
                }    
                    
                $dbh->exec("INSERT INTO ListeJoueur(IdEquipe, IdJoueur, Capitaine) VALUES($identiteEquipe,$_POST[idJoueur1],1)");

                $idExiste=0;
            $sql="SELECT IdJoueur FROM Joueur";
            foreach($dbh->query($sql) as $row){
                    if($_POST['idJoueur2']==$row['IdJoueur']){
                            $idExiste=1;
                    }
            }
                if($idExiste==0){

                $dbh->exec("INSERT INTO Joueur(IdJoueur,Nom,Prenom,DateNaissance,Categorie,AnneeInscription) VALUES('$_POST[idJoueur2]','$_POST[nom2]','$_POST[prenom2]','$dateN2',$categorie,'$dateI2')");
                }
                
                $dbh->exec("INSERT INTO ListeJoueur(IdEquipe, IdJoueur, Capitaine) VALUES($identiteEquipe,$_POST[idJoueur2],0)");
                
                $idExiste=0;
                $sql="SELECT IdJoueur FROM Joueur";
                foreach($dbh->query($sql) as $row){
                    if($_POST['idJoueur3']==$row['IdJoueur']){
                            $idExiste=1;
                    }
                }
                if($idExiste==0){

                $dbh->exec("INSERT INTO Joueur(IdJoueur,Nom,Prenom,DateNaissance,Categorie,AnneeInscription) VALUES('$_POST[idJoueur3]','$_POST[nom3]','$_POST[prenom3]','$dateN3',$categorie,'$dateI3')");
                }
                
                $dbh->exec("INSERT INTO ListeJoueur(IdEquipe, IdJoueur, Capitaine) VALUES($identiteEquipe,$_POST[idJoueur3],0)");
                $idExiste=0;
                $sql="SELECT IdJoueur FROM Joueur";
                foreach($dbh->query($sql) as $row){
                    if($_POST['idJoueur4']==$row['IdJoueur']){
                            $idExiste=1;
                    }
                }
                if($idExiste==0){
                $dbh->exec("INSERT INTO Joueur(IdJoueur,Nom,Prenom,DateNaissance,Categorie,AnneeInscription) VALUES('$_POST[idJoueur4]','$_POST[nom4]','$_POST[prenom4]','$dateN4',$categorie,'$dateI4')");
                }
                $dbh->exec("INSERT INTO ListeJoueur(IdEquipe, IdJoueur, Capitaine) VALUES($identiteEquipe,$_POST[idJoueur4],0)");
                $idExiste=0;
                $sql="SELECT IdJoueur FROM Joueur";
                foreach($dbh->query($sql) as $row){
                    if($_POST['idJoueur5']==$row['IdJoueur']){
                            $idExiste=1;
                    }
                }
                if($idExiste==0){
                $dbh->exec("INSERT INTO Joueur(IdJoueur,Nom,Prenom,DateNaissance,Categorie,AnneeInscription) VALUES('$_POST[idJoueur5]','$_POST[nom5]','$_POST[prenom5]','$dateN5',$categorie,'$dateI5')");
                }
                
                $dbh->exec("INSERT INTO ListeJoueur(IdEquipe, IdJoueur, Capitaine) VALUES($identiteEquipe,$_POST[idJoueur5],0)");
                $login=$_SESSION['historique']['login'];
                $dbh->exec("UPDATE Utilisateur SET Joueur=$_POST[idJoueur1] WHERE LoginU='$login'");
				switch($except){
					case 1:
						echo "<div class='alert alert-danger'>
						Erreur: format incorrect (attendus: JPEG/JPG/PNG).
						L'envoi de l'image a échoué.
						</div>";
					case 2:
						echo "<div class='alert alert-danger'>
						Erreur: le fichier existe déjà.
						L'envoi de l'image a échoué.
						</div>";
					case 0:
						echo "<div class='alert alert-success'>
						Fichier envoyé.
						</div>";
				}
                echo "<div class='alert alert-success'>Fécilicitations, vous êtes maintenant le capitaine de l'équipe : $_POST[nomEquipe]</div>";

            } 
        }
    }
}

    else {                  echo "<div class='alert alert-danger'> Vous devez vous connecter pour créer une équipe. </div>";
        echo "<meta http-equiv='refresh' content='2;URL=LoginUtilisateur.php?redirection=LoginUtilisateur.php'/>";

    }
              
                    include("../CadreStatique/skel2.php");
                    ?>
</html>
