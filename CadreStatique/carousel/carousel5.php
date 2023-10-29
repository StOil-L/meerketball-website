<ul id="carou5ul" class="carou5">
<?php 

$sqlBand5 = " SELECT DISTINCT Nom, TypeT, Images, Lieu 
              FROM Tournoi 
              WHERE DatesDebut < NOW()
              AND EtatTournoi Like 'En Cours' 
              ORDER BY DatesDebut";


$resultBand5 = $bddBand->query($sqlBand5);
$entreesBand5 = $resultBand5->fetchALL(PDO::FETCH_OBJ);
$n=0;
foreach ($entreesBand5 as $tou) {
  echo"<li>";
  echo $tou->Nom . " (" .$tou->Lieu . ") " . "- " . $tou->TypeT . "<br>";
  echo"</li>";
  if (++$n == 5) {break;}
}
?>
</ul>
<div id="carousel5" class="carousel slide img-fluid" data-ride="carousel" data-interval="2500">
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
      echo $entreesBand5[$i]->Images;
      echo "' class='d-block'>";
      echo "</div><br>";
    }
 ?>
</p>
 
 <?php // mise en php pour commenter sinon enlever balise pour faire apparaitre les contorleurs
//  <a id="carControl1" class="carousel-control-prev" href="#carousel5" role="button" data-slide="prev">
//  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
//  <!-- <span class="sr-only">précédent</span> -->
// </a>
// <a id="carControl2" class="carousel-control-next" href="#carousel5" role="button" data-slide="next">
//  <span class="carousel-control-next-icon" aria-hidden="true"></span>
//  <!-- <span class="sr-only">suivant</span> -->
// </a>
 ?> 

  

</div>