<ul>
<?php 

$sqlBand6 = " SELECT DISTINCT Nom, TypeT, Images, Lieu 
              FROM Tournoi 
              WHERE DatesDebut > NOW()
              AND EtatTournoi Like 'Phase inscription' 
              ORDER BY DatesDebut";
              
$resultBand6 = $bddBand->query($sqlBand6);
$entreesBand6 = $resultBand6->fetchALL(PDO::FETCH_OBJ);
$n=0;
foreach ($entreesBand6 as $tou2) {
  echo"<li>";
  echo $tou2->Nom . " (" . $tou2->Lieu . ") " . " - " . $tou2->TypeT . "<br>";
  echo"</li>";
  if (++$n == 5) {break;}
}
?>
</ul>
<div id="carousel6" class="carousel slide img-fluid" data-ride="carousel" data-interval="2600">
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
      echo $entreesBand6[$i]->Images;
      echo "' class='d-block'>";
      echo "</div><br>";
    }
 ?>
</p>

<?php // mise en php pour commenter sinon enlever balise pour faire apparaitre les contorleurs
//   <a id="carControl1" class="carousel-control-prev" href="#carousel6" role="button" data-slide="prev">
//   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
//   <!-- <span class="sr-only">précédent</span> -->
// </a>
// <a id="carControl2" class="carousel-control-next" href="#carousel6" role="button" data-slide="next">
//   <span class="carousel-control-next-icon" aria-hidden="true"></span>
//   <!-- <span class="sr-only">suivant</span> -->
// </a>
?>
  

</div>