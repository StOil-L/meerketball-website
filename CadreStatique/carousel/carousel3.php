<ul id="carou3ul" class="carou3">
<?php $sqlBand3=" SELECT Equipe1, Equipe2, Lieu, Images, Tournoi 
                    FROM Rencontre 
                    WHERE DateRencontre >= NOW() 
                    AND EtatRencontre Like 'A venir' 
                    OR EtatRencontre Like 'En attente' 
                    ORDER BY DateRencontre";

        $resultBand3 = $bddBand->query($sqlBand3);
        $entreesBand3 = $resultBand3->fetchALL(PDO::FETCH_OBJ);
        $n=0;$eq2;$eq1;$tour;
        foreach ($entreesBand3 as $renc) {
            // ici je pourrais factoriser mais je perdrais l'ordre de la database : cad que equipe 1 est locale et equipe 2 exterieure
            $sqlBand3eq1 =" SELECT Nom 
                            FROM Equipe 
                            WHERE IdEquipe = $renc->Equipe1";
            $resultBand3eq1 = $bddBand->query($sqlBand3eq1);
            $entreesBand3eq1 = $resultBand3eq1->fetchALL(PDO::FETCH_OBJ);
            
            foreach ($entreesBand3eq1 as $equip1) {
                $eq1 = $equip1->Nom;
            }

            $sqlBand3eq2="  SELECT Nom 
                            FROM Equipe 
                            WHERE IdEquipe = $renc->Equipe2";
            $resultBand3eq2 = $bddBand->query($sqlBand3eq2);
            $entreesBand3eq2 = $resultBand3eq2->fetchALL(PDO::FETCH_OBJ);
            
            foreach ($entreesBand3eq2 as $equip2) {
                $eq2 = $equip2->Nom;
            }

            $sqlBand3tour=" SELECT Nom 
                            FROM Tournoi
                            WHERE IdTournoi = $renc->Tournoi";
            $resultBand3tour = $bddBand->query($sqlBand3tour);
            $entreesBand3tour = $resultBand3tour->fetchALL(PDO::FETCH_OBJ);
            
            foreach ($entreesBand3tour as $tourn) {
                $tour = $tourn->Nom;
            }
            echo"<li>";
            echo $tour . " (" . $renc->Lieu . ") " . " : ". $eq1 . " VS " . $eq2  . "<br>";
            echo"</li>";
            if (++$n == 5) {break;}
        }?>
</ul>
<div id="carousel3" class="carousel slide carou3 img-fluid" data-ride="carousel" data-interval="2500">
  <p>
<?php
  $min= min(5,$n);
    for($i=0;$i<$min;$i++){
      if($i==1){
        echo"<div class='carousel-item active divImgCar'>";
      } else {
        echo"<div class='carousel-item divImgCar'>";
      }
      echo "<img src='../ImagesUtilisateur/Tournois/";
      echo $entreesBand3[$i]->Images;
      echo "' class='d-block'>";
      echo "</div><br>";
    }
 ?>
</p>
 
<?php // mise en php pour commenter sinon enlever balise pour faire apparaitre les contorleurs
  // <a id="carControl1" class="carousel-control-prev" href="#carousel3" role="button" data-slide="prev">
  //   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
  //   <!-- <span class="sr-only">précédent</span> -->
  // </a>
  // <a id="carControl2" class="carousel-control-next" href="#carousel3" role="button" data-slide="next">
  //   <span class="carousel-control-next-icon" aria-hidden="true"></span>
  //   <!-- <span class="sr-only">suivant</span> -->
  // </a>
?>
</div>