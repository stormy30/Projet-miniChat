
<?php
// Connexion à la base de données
 $DB_HOST = 'localhost';
 $DB_USER = 'root';
 $DB_PASS = 'coda';
 $DB_NAME = 'minichat';

// Insertion du message à l'aide d'une requête préparée
try{
     $DB_con = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME}",$DB_USER,$DB_PASS);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     $DB_con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
 }
 catch(PDOException $e){
     echo $e->getMessage();
 }


?>
<!DOCTYPE>
<html>

<head>
    <title>MiniChat Project II - The Return</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Material Design Light -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-pink.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
    <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</head>

<body>
    <div class="mdl-layout mdl-js-layout">
        <main class="mdl-layout__content">
            <div class="page-content">
                <ul class="demo-list-item mdl-list" id="conversation">

                <?php

                $reponse = $DB_con->query("SELECT COUNT(*) AS nb_message FROM tchat");
                $reponse1 = $reponse->fetchAll();

                foreach ($reponse1 as $value) {

                    echo '<p>Nombre de message :'.$value->nb_message.'</p>';
                }

                //creation d'une variable pour définir le du nombre de messages à afficher par pages
                $nombre_message = $value->nb_message;
                // création d' une variable qui va calculer le nombre de pages en fonction de messages total et
                // le nombre de messages que l'on veut afficher par page
                $nombre_message_page = 10;

                $nombre_page = ceil($nombre_message / $nombre_message_page);

                // On affiche le résultat
                echo '<p>Nombre de Pages : '.$nombre_page.'</p>';
                echo 'Page : ';
                //création d' un compteur pour définir le nombre de pages
                for ($i=1; $i <= $nombre_page; $i ++) {
                // On affiche le nombre de pages afin de pouvoir les parcourir
                    echo '<a href="index.php?page=' . $i . '"><button >' . $i . '</button></a>';
                }

                if (isset($_GET['page']))
                {
                    $page = $_GET['page']; // On récupère le numéro de la page indiqué dans l'adresse
                }
                else // La variable n'existe pas, c'est la première fois qu'on charge la page
                {
                    $page = 1; // On se met sur la page 1 (par défaut)
                }
                // On calcule le numéro du premier message qu'on prend pour le LIMIT de MySQL
                $premier_message_afficher = ($page - 1) * $nombre_message_page;

                // Récupération des 10 derniers messages

                $reponse= $DB_con->query('SELECT pseudo, message FROM minichat.tchat ORDER BY id DESC LIMIT '
                    . $premier_message_afficher .', ' . $nombre_message_page);

                // Affichage de chaque message (toutes les données sont protégées par htmlspecialchars)

                $donnees = $reponse->fetchAll();

                //recuperation du nombre de message par page transformé en variable dans le include messageparpage

                foreach ($donnees as $reponse) {
                    $donnees->message = str_replace(':smile_cat:','<img style="width: 30px; height: 30px" src="smile_cat.png"/>', $donnees->message);

                   ?>

                    <li class="mdl-list__item">
                            <span class="mdl-list__item-primary-content">
                                 <strong><?php echo htmlspecialchars($reponse->pseudo); ?></strong>: <?php echo htmlspecialchars($reponse->message); ?>
                            </span>
                    </li>


                    <?php
                }
                ?>
                </ul>

                <p>

                </p>
<!--                ici dans le l'action du formulaire on va lier le fichier inscriptionpseudo.php qui va  servir a etablir la requette
pour l'insertion des imputs dans la base de donnée avec la methode post-->
                <form action="inscriptionpseudo.php" class="mdl-grid" method="POST">
                    <div class="mdl-cell mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="pseudo" id="pseudo">
                        <label class="mdl-textfield__label" for="sample3">Pseudo</label>
                    </div>
                    <div class="mdl-cell mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="message" id="message">
                        <label class="mdl-textfield__label" for="sample3">Message</label>
                    </div>
                    <button id="send" class="mdl-cell mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored">
                        <i class="material-icons">send</i>
                    </button>
                </form>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
    <!-- Material Design Light -->
    <script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</body>
</html>
