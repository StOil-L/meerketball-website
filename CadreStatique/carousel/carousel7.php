<ul id="carou7ul" class="carou7">
<?php

$sqlBand7 = " SELECT DISTINCT IdEquipe, IdTournoi, DateInscription
              FROM ListeEquipe 
              ORDER BY DateInscription desc
              LIMIT 5";


$resultBand7 = $bddBand->query($sqlBand7);
$entreesBand7 = $resultBand7->fetchALL(PDO::FETCH_OBJ);
$n = 0;$k=0;
$toNom;
$toType;
$toLieu;
$equ;
$ima1;$ima2;$ima3;$ima4;$ima5;
foreach ($entreesBand7 as $insctou) {
  $sqlBand7tour = " SELECT Nom, TypeT, Lieu 
                    FROM Tournoi
                    WHERE IdTournoi = $insctou->IdTournoi";
  $resultBand7tour = $bddBand->query($sqlBand7tour);
  $entreesBand7tour = $resultBand7tour->fetchALL(PDO::FETCH_OBJ);
  foreach ($entreesBand7tour as $tourno) {
    $toNom = $tourno->Nom;
    $toType = $tourno->TypeT;
    $toLieu = $tourno->Lieu;
  }
  $sqlBand7equ = "  SELECT Nom, Images 
                    FROM Equipe
                    WHERE IdEquipe = $insctou->IdEquipe";
  $resultBand7equ = $bddBand->query($sqlBand7equ);
  $entreesBand7equ = $resultBand7equ->fetchALL(PDO::FETCH_OBJ);
  foreach ($entreesBand7equ as $equip) {
    $equ = $equip->Nom;
    $k++;
    switch ($k) {
      case "1":
        $ima1=$equip->Images;
        break;
      case "2":
        $ima2=$equip->Images;
        break;
      case "3":
        $ima3=$equip->Images;
        break;
      case "4":
        $ima4=$equip->Images;
        break;
      case "5":
        $ima5=$equip->Images;
        break;
    }
  }
  echo"<li>";
  echo $toNom . " (" . $toLieu . ") " . " - " . $toType . " : " . $equ . "<br>";
  echo"</li>";
  if (++$n == 5) {break;
  }
}
?>
</ul>
<div id="carousel7" class="carousel slide img-fluid" data-ride="carousel" data-interval="2700">
  <p>
    <?php
    $min = min(5, $n);
    for ($i = 0; $i < $min; $i++) {
      if ($i == 1) {
        echo "<div class='carousel-item active divImgCar'>";
      } else {
        echo "<div class='carousel-item divImgCar'>";
      }
      echo "<img src='../ImagesUtilisateur/Tournois/";
      switch ($i) {
        case "1":
          echo $ima1;
          break;
        case "2":
          echo $ima2;
          break;
        case "3":
          echo $ima3;
          break;
        case "4":
          echo $ima4;
          break;
        case "5":
          echo $ima5;
          break;
      }
      echo "' class='d-block'>";
      echo "</div><br>";
    }
    ?>
  </p>

<?php // mise en php pour commenter sinon enlever balise pour faire apparaitre les contorleurs
//   <a id="carControl1" class="carousel-control-prev" href="#carousel7" role="button" data-slide="prev">
//   <span class="carousel-control-prev-icon" aria-hidden="true"></span>
//   <!-- <span class="sr-only">précédent</span> -->
// </a>
// <a id="carControl2" class="carousel-control-next" href="#carousel7" role="button" data-slide="next">
//   <span class="carousel-control-next-icon" aria-hidden="true"></span>
//   <!-- <span class="sr-only">suivant</span> -->
// </a>
?>


</div>