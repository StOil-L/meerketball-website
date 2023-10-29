<?php
include("connexion.php");
date_default_timezone_set('Europe/Paris');

try {
    $bddBand = new PDO('mysql:host=localhost;dbname=suricates;charset=UTF8', $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
} catch (PDOException $e) {
    echo $e->getMessage();
    die("connexion Impossible !");
}
?>
<div id="rowband0" class="row">
    <button id="btnEvent" onclick="carouband()"> Évènements </button>
</div>
<div id="rowband1" class="row">
    <button onclick="carouband3()"> Prochaines rencontres </button>
    <div id="rowbandinclude3" class="row rowbandinclude">
        <p>
            <?php
            include("../CadreStatique/carousel/carousel3.php");
            ?>
        </p>

    </div>
</div>
<div id="rowband2" class="row">
    <button onclick="carouband4()"> Dernières rencontres </button>
    <div id="rowbandinclude4" class="row rowbandinclude">
        <p>
            <?php
            include("../CadreStatique/carousel/carousel4.php");
            ?>
        </p>
    </div>
</div>
<div id="rowband3" class="row">
    <button onclick="carouband5()"> Tournois en cours </button>
    <div id="rowbandinclude5" class="row rowbandinclude">
        <p>
            <?php
            include("../CadreStatique/carousel/carousel5.php");
            ?>
        </p>

    </div>
</div>
<div id="rowband4" class="row">
    <button onclick="carouband6()"> Tournois à venir </button>
    <div id="rowbandinclude6" class="row rowbandinclude">
        <p>
            <?php
            include("../CadreStatique/carousel/carousel6.php");
            ?>
        </p>

    </div>
</div>
<div id="rowband5" class="row">
    <button onclick="carouband7()"> Derniers inscrits tournois </button>
    <div id="rowbandinclude7" class="row rowbandinclude">
        <p><?php
            include("../CadreStatique/carousel/carousel7.php");
            ?></p>

    </div>
</div>
<script>
    function carouband() {

        var x = document.getElementById("rowbandinclude3");
        var y = document.getElementById("rowbandinclude4");
        var z = document.getElementById("rowbandinclude5");
        var w = document.getElementById("rowbandinclude6");
        var v = document.getElementById("rowbandinclude7");
        if (!x.style.display) {
            x.style.display= "none";
            y.style.display= "none";
            z.style.display= "none";
            w.style.display= "none";
            v.style.display= "none";
        }
        if (x.style.display === "none") {
            x.style.display = "block";
            y.style.display = "block";
            z.style.display = "block";
            w.style.display = "block";
            v.style.display = "block";
        } else {
            x.style.display = "none";
            y.style.display = "none";
            z.style.display = "none";
            w.style.display = "none";
            v.style.display = "none";
        }
    }

    function carouband3() {
        var x = document.getElementById("rowbandinclude3");
        if (!x.style.display) {x.style.display= "none";}
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }

    function carouband4() {
        var y = document.getElementById("rowbandinclude4");
        if (!y.style.display) {y.style.display= "none";}
        if (y.style.display === "none") {
            y.style.display = "block";
        } else {
            y.style.display = "none";
        }
    }

    function carouband5() {
        var z = document.getElementById("rowbandinclude5");
        if (!z.style.display) {z.style.display= "none";}
        if (z.style.display === "none") {
            z.style.display = "block";
        } else {
            z.style.display = "none";
        }
    }

    function carouband6() {
        var w = document.getElementById("rowbandinclude6");
        if (!w.style.display) {w.style.display= "none";}
        if (w.style.display === "none") {
            w.style.display = "block";
        } else {
            w.style.display = "none";
        }
    }

    function carouband7() {
        var v = document.getElementById("rowbandinclude7");
        if (!v.style.display) {v.style.display= "none";}
        if (v.style.display === "none") {
            v.style.display = "block";
        } else {
            v.style.display = "none";
        }
    }
</script>
<?php $bddBand = null; ?>