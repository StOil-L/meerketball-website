<?php
        include("../CadreStatique/connexion.php");
        date_default_timezone_set('Europe/Paris');

        try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=UTF8", $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (PDOException $e) {
        echo $e->getMessage();
        die("connexion Impossible !");
        }

        $titreOnglet = "Tournois";
        $linkPage = "../Formulaires/ListeDesTournois.php";
        $titrePage = "Liste des tournois";

        ?>
        <!doctype html>
        <?php

        echo "<h1>Liste des Tournois</h1>";

        echo "<div id='frise'>";
        echo "<button onclick='open_close_e()' id='frisee'>Tournois en cours</button>";
        echo "<button onclick='open_close_t()' id='friset'>Tournois terminés</button>";
        echo "<button onclick='open_close_a()' id='frisea'>Tournois à venir</button>";
        echo "</div>";

        echo "<br />";

        // Requête : TOURNOIS EN COURS
        echo "<div id='encours'>";
        $sql = "SELECT IdTournoi, Nom, TypeT FROM Tournoi WHERE EtatTournoi='En cours' ORDER BY DatesDebut";
        //$sql = "SELECT IdTournoi, Nom, TypeT FROM Tournoi WHERE DatesDebut < NOW() AND ADDDATE(DatesDebut, INTERVAL Duree DAY)> NOW() ORDER BY DatesDebut";
        $result = $conn->query($sql);

        echo "<h2>Tournois en cours</h2>"; // Titre de la catégorie
        //echo "<p>Veuillez noter que le déroulement des tournois de cette catégorie n'est pas complet</p>"; // À voir si je garde ou pas
        echo "<ul>"; // Début de la liste des tournois en cours

        if ($row = $result->fetch()) // S'il y a un ou des résultat.s
        {
        do {
            if ($row['Nom'] !== NULL) // Si le tournoi a un nom
            {
            echo "<li>" . "Tournoi n° " . $row['IdTournoi'] . " - " . $row['Nom'] . "</li>"; // Afficher chaque tournoi comme élément de la liste (nom et numéro, ça rend moins bizarre le bouton en attendant d'avoir mieux)

            } else // Si le tournoi n'a pas de nom
            {
            echo "<li>" . "Tournoi n° " . $row['IdTournoi'] . "</li>"; // Afficher chaque tournoi comme élément de la liste (numéro)

            }
			if($row['TypeT'] == 'Championnat') {
				echo "<form action='../Formulaires/Championnat.php' method='post'><input type='hidden' name='championnatChoix' value=''>
                <button class='affichage' type='submit' name='championnatChoisi' value='" . $row['IdTournoi'] . "'>Afficher le déroulement</button>
            </form>";
			} else {
            echo "<form action='../Formulaires/AffichageTournoiChoisi.php' method='post'>
                <button class='affichage' type='submit' name='Tournoi' value='" . $row['IdTournoi'] . "'>Afficher le déroulement</button>
            </form>"; // Bouton sur lequel on clique pour accéder à la page affichage.php où est le déroulement du tournoi
            // IdTournoi est passé grâce à la valeur du bouton -> il faut changer ça pour éviter d'avoir IdTournoi sur le bouton
            // Pour l'instant, l'url finit donc par .php?Tournoi=IdTournoi
			}
            echo "<br />";
        } while ($row = $result->fetch());
        } else {
        echo "<div class='text-sans-classe'>Aucun tournoi dans cette catégorie</div>"; // S'il n'y a aucun tournoi, on l'affiche
        }

        echo "</ul>"; // Fin de la liste des tournois en cours
        echo "</div>";




        // Requête : TOURNOIS TERMINÉS
        echo "<div id='terminés'>";
        $sql = "SELECT IdTournoi, Nom, TypeT FROM Tournoi WHERE EtatTournoi='Acheve' ORDER BY DatesDebut";
        //$sql = "SELECT IdTournoi, Nom, TypeT FROM Tournoi WHERE DatesDebut < NOW() AND ADDDATE(DatesDebut, INTERVAL Duree DAY) < NOW() ORDER BY DatesDebut";
        $result = $conn->query($sql);

        echo "<h2>Tournois terminés</h2>"; // Titre de la catégorie
        echo "<ul>"; // Début de la liste des tournois terminés

        if ($row = $result->fetch()) // S'il y a un ou des résultat.s
        {
        do {
            if ($row['Nom'] !== NULL) // Si le tournoi a un nom
            {
            echo "<li>" . "Tournoi n° " . $row['IdTournoi'] . " - " . $row['Nom'] . "</li>"; // Afficher chaque tournoi comme élément de la liste (nom et numéro, ça rend moins bizarre le bouton en attendant d'avoir mieux)

            } else // Si le tournoi n'a pas de nom
            {
            echo "<li>" . "Tournoi n° " . $row['IdTournoi'] . "</li>"; // Afficher chaque tournoi comme élément de la liste (numéro)
            }
			if($row['TypeT'] == 'Championnat') {
				echo "<form action='../Formulaires/Championnat.php' method='post'><input type='hidden' name='championnatChoix' value=''>
                <button class='affichage' type='submit' name='championnatChoisi' value='" . $row['IdTournoi'] . "'>Afficher le déroulement</button>
            </form>";
			} else {
            echo "<form action='../Formulaires/AffichageTournoiChoisi.php' method='post'>
                <button class='affichage' type='submit' name='Tournoi' value='" . $row['IdTournoi'] . "'>Afficher le déroulement</button>
            </form>"; // Bouton sur lequel on clique pour accéder à la page affichage.php où est le déroulement du tournoi
            // IdTournoi est passé grâce à la valeur du bouton -> il faut changer ça pour éviter d'avoir IdTournoi sur le bouton
            // Pour l'instant, l'url finit donc par .php?Tournoi=IdTournoi
			}
            echo "<br />";
            // traiter $row
        } while ($row = $result->fetch());
        } else {
        echo "<div class='text-sans-classe'>Aucun tournoi dans cette catégorie</div>"; // S'il n'y a aucun tournoi, on l'affiche
        }

        echo "</ul>"; // Fin de la liste des tournois terminés
        echo "</div>";




        // Requête : TOURNOIS À VENIR
        echo "<div id='avenir'>";
        $sql = "SELECT IdTournoi, Nom, DatesDebut FROM Tournoi WHERE EtatTournoi='Phase inscription' ORDER BY DatesDebut";
        //$sql = "SELECT IdTournoi, Nom, DatesDebut FROM Tournoi WHERE DatesDebut > NOW() ORDER BY DatesDebut";
        $result = $conn->query($sql);

        echo "<h2>Tournois à venir</h2>"; // Titre de la catégorie
        //echo "<p>Veuillez noter que le déroulement des tournois de cette catégorie n'est pas encore disponible</p>"; // À voir si je garde ou pas
        echo "<ul>"; // Début de la liste des tournois à venir

        if ($row = $result->fetch()) // S'il y a un ou des résultat.s
        {
        do {
            if ($row['Nom'] !== NULL) // Si le tournoi a un nom
            {
            echo "<li class='lifutur'>" . "Tournoi n° " . $row['IdTournoi'] . " - " . $row['Nom'] . "<br/>Début du tournoi : ". $row['DatesDebut'] ."</li>"; // Afficher chaque tournoi comme élément de la liste (nom et numéro, ça rend moins bizarre le bouton en attendant d'avoir mieux)

            } else // Si le tournoi n'a pas de nom
            {
            echo "<li class='lifutur'>" . "Tournoi n° " . $row['IdTournoi'] . "</li>"; // Afficher chaque tournoi comme élément de la liste (numéro)

            }
        } while ($row = $result->fetch());
        } else {
        echo "<div class='text-sans-classe'>Aucun tournoi dans cette catégorie</div>"; // S'il n'y a aucun tournoi, on l'affiche
        }

        echo "</ul>"; // Fin de la liste des tournois à venir
        echo "</div>";

        if (isset($_SESSION['historique']['dalt']) && ($_SESSION['historique']['dalt'] == 1))
        {
        ?>
        <script>

        function open_close_e() {
            var x = document.getElementById('encours');
            var b = document.getElementById('frisee');
            if (x.style.display === 'none') {
            x.style.display = 'block';
            b.style.cssText = 'background-color: rgb(32, 32, 32); color: var(--gris4)';
            } else {
            x.style.display = 'none';
            b.style.cssText = 'background-color: rgba(32, 32, 32, 0.5); color: var(--gris4)';
            }
        }


        function open_close_t() {
            var x = document.getElementById('terminés');
            var b = document.getElementById('friset');
            if (x.style.display === 'none') {
            x.style.display = 'block';
            b.style.cssText = 'background-color: rgb(32, 32, 32); color: var(--gris4)';
            } else {
            x.style.display = 'none';
            b.style.cssText = 'background-color: rgba(32, 32, 32, 0.5); color: var(--gris4)';
            }
        }

        function open_close_a() {
            var x = document.getElementById('avenir');
            var b = document.getElementById('frisea');
            if (x.style.display === 'none') {
            x.style.display = 'block';
            b.style.cssText = 'background-color: rgb(32, 32, 32); color: var(--gris4)';

            } else {
            x.style.display = 'none';
            b.style.cssText = 'background-color: rgba(32, 32, 32, 0.5); color: var(--gris4)';
            }
        }
        </script>
        <?php
        }
        else
        {
        ?>
        <script>

        function open_close_e() {
            var x = document.getElementById("encours");
            var b = document.getElementById('frisee');
            if (x.style.display === "none") {
            x.style.display = "block";
            b.style.cssText = 'background-color: rgb(6, 34, 94)';
            

            } else {
            x.style.display = "none";
            b.style.cssText = 'background-color: rgba(6, 34, 94, 0.5)';
            }
        }


        function open_close_t() {
            var x = document.getElementById("terminés");
            var b = document.getElementById('friset');
            if (x.style.display === "none") {
            x.style.display = "block";
            b.style.cssText = 'background-color: rgb(6, 34, 94)';
            } else {
            x.style.display = "none";
            b.style.cssText = 'background-color: rgba(6, 34, 94, 0.5)';
            }
        }

        function open_close_a() {
            var x = document.getElementById("avenir");
            var b = document.getElementById('frisea');
            if (x.style.display === "none") {
            x.style.display = "block";
            b.style.cssText = 'background-color: rgb(6, 34, 94)';

            } else {
            x.style.display = "none";
            b.style.cssText = 'background-color: rgba(6, 34, 94, 0.5)';
            }
        }
        </script>
        <?php
        }
        ?>

        </html>
