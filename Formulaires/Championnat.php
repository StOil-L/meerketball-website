<?php
session_start();
include("connexion.php");
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

<h1>Championnat</h1>


<?php

$sql="SELECT IdTournoi, Nom FROM Tournoi WHERE TypeT='Championnat'";
echo"    
    <form method=post action=Championnat.php>
    <select name='championnatChoisi' >
    <option value=''>--choisissez un championnat--</option>
    ";
    foreach($dbh->query($sql) as $row){ 

    echo "<option value=$row[IdTournoi]>$row[Nom]</option>";
    }
    echo "</select>
<button type=submit class='click' name=championnatChoix> Valider </button>

</form>";

if(isset($_POST['championnatChoix'])){
        if(isset($_POST['championnatChoisi'])){
             if($_POST['championnatChoisi'] !=''){
            

                 $_SESSION['historique']['champ']=$_POST['championnatChoisi'];
                 $idtournoi=$_SESSION['historique']['champ'];   
				$classement=array();
                $sql1="SELECT Nom FROM Rencontre, Equipe WHERE Equipe1=IdEquipe AND Tournoi = $idtournoi GROUP BY Equipe1";
                foreach($dbh->query($sql1) as $row){
                       $name=$row['Nom'];
                       $classement[$name]=0;

                }
                $sql2="SELECT  count(Equipe1) as score,  Nom FROM Rencontre, Equipe, Championnat  WHERE Championnat.Rencontre=Rencontre.IdRencontre AND Equipe.IdEquipe=Equipe1 AND Rencontre.Tournoi='$idtournoi' AND Resultat IS NOT NULL AND Resultat=-1 GROUP BY Equipe1 ";
                foreach($dbh->query($sql2) as $row){
                    $name=$row['Nom'];
                    $classement[$name]=3*$row['score'];
                }
                $sql3="SELECT  count(Equipe2) as score,  Nom FROM Rencontre, Championnat, Equipe  WHERE Championnat.Rencontre=Rencontre.IdRencontre AND Equipe.IdEquipe=Equipe2 AND Rencontre.Tournoi='$idtournoi' AND Resultat IS NOT NULL AND Resultat=1 GROUP BY Equipe2 ";   
                
                foreach($dbh->query($sql3) as $row){
                 
                 $name=$row['Nom'];
                 $classement[$name]+=3*$row['score'];
           
                }

                $sql4="SELECT  count(Equipe1) as score,  Nom FROM Rencontre ,Championnat, Equipe  WHERE Championnat.Rencontre=Rencontre.IdRencontre AND Equipe.IdEquipe=Equipe1 AND Rencontre.Tournoi='$idtournoi' AND Resultat IS NOT NULL AND Resultat=0 GROUP BY Equipe1 ";
                     foreach($dbh->query($sql4) as $row){
                    $name=$row['Nom'];
                    $classement[$name]+=$row['score'];
                    }

                $sql5="SELECT count(Equipe2) as score, Nom FROM Rencontre ,Championnat, Equipe  WHERE Championnat.Rencontre=Rencontre.IdRencontre AND Equipe.IdEquipe=Equipe2 AND Rencontre.Tournoi='$idtournoi' AND Resultat IS NOT NULL AND Resultat=0 GROUP BY Equipe2 ";   
                
                foreach($dbh->query($sql5) as $row){
                 
					$name=$row['Nom'];
					$classement[$name]+=$row['score'];
           
                }
               
                $commence="Le Championnat n'a pas encore démarré";
                $sql1="SELECT Nom FROM Rencontre , Equipe WHERE Equipe1=IdEquipe AND Rencontre.Tournoi='$idtournoi' GROUP BY Equipe1 ";
                foreach($dbh->query($sql1) as $row){
                       $name=$row['Nom'];
                       $commence='vrai';
                        
                        
                }
               arsort($classement);

                foreach ($classement as $key => $val) {
                        echo "$key a  $val points <br/>";
                    }

            

                if($commence!='vrai'){
                echo $commence;
                }

            }

        }
    }
              
                    include("../CadreStatique/skel2.php");
                    ?>
</html>
