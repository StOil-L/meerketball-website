<html lang="fr">
<?php
include("connexion.php");
date_default_timezone_set('Europe/Paris');

try{
    $dbh=new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8",$user,$pass,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
    }
    
    catch(PDOException $e){
    echo $e->getMessage();
    die("Connexion impossible.");
    }
?>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../CadreStatique/css/bootstrap.css">
    <link rel="stylesheet" href="../CadreStatique/css/style.css">
    <?php
    if (isset($_SESSION['historique']['dalt'])) {
        if ($_SESSION['historique']['dalt'] == 1) {
            echo "<link rel='stylesheet' href='../CadreStatique/css/styledalto.css'>";
        }
    } else {
        echo "<link rel='stylesheet' href='../CadreStatique/css/style.css'>";
    }
    ?>
    <script src="../CadreStatique/js/slim.min.js"></script>
    <script src="../CadreStatique/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title><?= $titreOnglet ?></title>
</head>
<script>
     function hamburger() {
        var x = document.getElementById("myTopnav");
        if (x.className === "topnav") {
            x.className += " responsive";
        } else {
            x.className = "topnav";
        }
    }

    var show;

    function cascade() {
            if (sessionStorage.getItem("BandDisplay")=="1"){
                show_hide();
            }
    }
    
    function show_hide() {
        if (show==null) {
            document.getElementById("bandeau").style.display = "inline";
            document.getElementById("bandeau").className = "col-3 bandeau";
            document.getElementById("blocP").className = "col-9 blocP";
            sessionStorage.setItem("BandDisplay", "1");
            return show = 1;
        } else {
            document.getElementById("bandeau").style.display = "none";
            document.getElementById("bandeau").className = "col-0 bandeau";
            document.getElementById("blocP").className = "col-12 blocP";
            sessionStorage.setItem("BandDisplay", "0");
            return show = null;
        }
    }
    var dragValue;
	var enDeplacement = 0;

    function move2() {
        var element = document.getElementById("boutonBandeau");
        element.style.position = "absolute";
        element.style.margin = "-25px 0 0 0px";

        element.onmousedown = function() {
            dragValue = element;
			enDeplacement = 1;
        }
		
    }
    document.onmouseup = function(e) {
        dragValue = null;
		enDeplacement = 0;
    }
    document.onmousemove = function(e) {
        var x = e.pageX;
        var y = e.pageY;
		if(enDeplacement == 1) {
			dragValue.style.left = x + "px";
			dragValue.style.top = y + "px";
		}
    }
    function savePagePrec(){
        sessionStorage.setItem("pagePrec", location.href);
    }
    
</script>

<body onload="cascade()">


    <div id="parent" class="container-fluid bodyP">
        <div id="child1" class="header HBF0">
            <div id="rowhead" class="row">
                <div id="rowhead2" class="col-xl-1 col-12">
                    <a href="../CadreStatique/index1.php"><img src="../CadreStatique/img/logo.png"></a>
                </div>
                <div id="rowhead1" class="col-xl-5 col-12">
                    <a href=<?= $linkPage ?>><?= $titrePage ?></a>
                </div>
                <div id="rowhead4" class="col-xl-2 col-12">
                    <div class="col-xl-2">

                        <div id="boutonBandeau" class="boutonBandeau">
                            <button class="btn boutonBandeau" onmouseup="move2()" onclick="show_hide()">
                                Bandeau
                            </button>
                        </div>

                    </div>
                </div>
                <div id="rowhead5" class="col-xl-3 col-12">
                    <div class="col espaceMemb">
                        <?php
                        if (!isset($_SESSION['historique']['login'])) { ?>
                            <div id="falseLogged" class="row">
                                <div class="col-6">
                                    <a href="../Formulaires/LoginUtilisateur.php" onclick="savePagePrec()">
                                        Connexion
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="../Formulaires/Inscription.php">
                                        Inscription
                                    </a>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div id="trueLogged" class="row">
                                <div class="col-6">
                                    <a href="../Formulaires/Deconnexion.php">
                                        Déconnexion
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="../Formulaires/CompteUtilisateur.php">
                                        <?php echo $_SESSION['historique']['login']; ?>
                                    </a>
                                </div>
                            </div>
                        <?php
                        }   ?>
                    </div>
                </div>
            </div>
            <div id="child2" class="header HBF0 topnavbar row">
                <div id="rowhead3left" class="div col-4 col-sm-3 col-md-4"></div>
                <div id="rowhead3" class="col-12 col-sm-6 col-md-4">
                    <div class="topnav" id="myTopnav">
                        <a href="../CadreStatique/index1.php" class="active">Meerketball</a>

                        
                        <?php if(isset($_SESSION['historique']['login'])){
                            echo  "<a href='../Formulaires/Equipe.php'>
                            Créer une équipe
                        </a>";
                        echo  "<a href='../Formulaires/Preinscription.php'>
                        Inscrire mon équipe à un tournoi
                    </a>";
                        }  ?>
                        <?php if(isset($_SESSION['historique']['login'])){
                            $login=$_SESSION['historique']['login'];
                                $sql="SELECT RoleU FROM Utilisateur WHERE LoginU='$login'";
                                foreach($dbh->query($sql) as $row){
                                    
                                    if($row['RoleU']=='Administrateur'){
                                        echo  "<a href='../Formulaires/CreationTournoi.php'>
                                                Création tournoi
                                        </a>";         
                                            if($row['RoleU']=='Gestionnaire' || $row['RoleU']=='Administrateur'){
                                        echo  "<a href='../Formulaires/GestionTournoi.php'>
                                             Gestion de tournoi
                                     </a>";
                                            }
                                            }
                                     else  if($row['RoleU']=='Gestionnaire'){
                                        echo  "<a href='../Formulaires/GestionTournoi.php'>
                                        Gestion de tournoi
                                </a>";
                                     }
                                              
                                       
                                    }
                                }
                        

                        ?>
                        <a href="../Formulaires/Championnat.php">
                            Classement Championnat
                        </a>
                        <a href="../AutresPages/FAQ.php">
                            FAQ
                        </a>
                        <a href="../AutresPages/Assistance.php">
                            Assistance
                        </a>
                        <?php
                        if (isset($_SESSION['historique']['RoleU'])) {
                            if ($_SESSION['historique']['RoleU'] == "Administrateur") {
                        ?>
                                <a href="../AutresPages/PageAdmin.php">
                                    Page Administrateur
                                </a>
                        <?php
                            }
                        }
                        ?>

                        <a href="javascript:void(0);" class="icon" onclick="hamburger()">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                </div>
                <div id="rowhead3right" class="div col-4 col-sm-3 col-md-4"></div>
            </div>
        </div>
        <div id="child3" class="blocP HBF0">
            <!-- <div id="wraper" class="container-fluid HBF"> -->
            <div id="rowwrap" class="row">
                <div id="blocP" class="col-12 blocP">
