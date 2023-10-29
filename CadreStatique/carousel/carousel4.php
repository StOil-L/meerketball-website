<ul>
<?php 
echo "[Vainqueur]"."<br>";
$sqlBand4 = "   SELECT IdRencontre, Equipe1, Equipe2, Lieu, Vainqueur, Tournoi, Images 
                FROM Rencontre 
                WHERE DateRencontre <= NOW() 
                AND EtatRencontre Like 'Annule' 
                OR EtatRencontre Like 'Termine' 
                ORDER BY DateRencontre desc";


$resultBand4 = $bddBand->query($sqlBand4);
$entreesBand4 = $resultBand4->fetchALL(PDO::FETCH_OBJ);
$n=0;$eq1;$eq2;$tour;$vainq;
foreach ($entreesBand4 as $renc) {

  $sqlBand4eq1 =" SELECT IdEquipe, Nom 
                  FROM Equipe 
                  WHERE IdEquipe = $renc->Equipe1";
  $resultBand4eq1 = $bddBand->query($sqlBand4eq1);
  $entreesBand4eq1 = $resultBand4eq1->fetchALL(PDO::FETCH_OBJ);
  foreach ($entreesBand4eq1 as $equip1) {
      $eq1 = $equip1->Nom;
  }

  $sqlBand4eq2="  SELECT Nom 
                  FROM Equipe 
                  WHERE IdEquipe = $renc->Equipe2";
  $resultBand4eq2 = $bddBand->query($sqlBand4eq2);
  $entreesBand4eq2 = $resultBand4eq2->fetchALL(PDO::FETCH_OBJ);
  foreach ($entreesBand4eq2 as $equip2) {
      $eq2 = $equip2->Nom;
  }

  $sqlBand4tour=" SELECT Nom 
                  FROM Tournoi
                  WHERE IdTournoi = $renc->Tournoi";
  $resultBand4tour = $bddBand->query($sqlBand4tour);
  $entreesBand4tour = $resultBand4tour->fetchALL(PDO::FETCH_OBJ);
  foreach ($entreesBand4tour as $tourn) {
      $tour = $tourn->Nom;
  }

  if(isset($renc->Vainqueur)){
    $sqlBand4vainq="  SELECT Nom 
                    FROM Equipe 
                    WHERE IdEquipe = $renc->Vainqueur";
    $resultBand4vainq = $bddBand->query($sqlBand4vainq);
    $entreesBand4vainq = $resultBand4vainq->fetchALL(PDO::FETCH_OBJ);
    foreach ($entreesBand4vainq as $vainqueur) {
        $vainq = $vainqueur->Nom;
    }
  }
  echo"<li>";
  if ($vainq===$eq1){
    echo $tour . " ("  . $renc->Lieu . ") " . " : " . " [" . $eq1 . "] " . " VS " . $eq2  . "<br>";
  }else if($vainq===$eq2){
    echo $tour . " ("  . $renc->Lieu . ") " . " : " . $eq1 . " VS " . " [" . $eq2 . "] "  . "<br>";
  } else {
    echo $tour . " ("  . $renc->Lieu . ") " . " : " . $eq1 . " VS " . $eq2  . "<br>";
  }
  echo"</li>";
  if (++$n == 5) {break;}
}?>
</ul>
<div id="carousel4" class="carousel slide img-fluid" data-ride="carousel" data-interval="2500">
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
      echo $entreesBand4[$i]->Images;
      echo "' class='d-block'>";
      echo "</div><br>";
    }
 ?>
</p>
<?php // mise en php pour commenter sinon enlever balise pour faire apparaitre les contorleurs
//   <a id="carControl1" class="carousel-control-prev" href="#carousel4" role="button" data-slide="prev">
//   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
//   <!-- <span class="sr-only">précédent</span> -->
// </a>
// <a id="carControl2" class="carousel-control-next" href="#carousel4" role="button" data-slide="next">
//   <span class="carousel-control-next-icon" aria-hidden="true"></span>
//   <!-- <span class="sr-only">suivant</span> -->
// </a>
?>  




</div>