<?php
    //******************** Déclarations des variables ********************
    // Inclusion du fichier de configuration
     include($niveau . 'inc/scripts/config.inc.php');

    //Déclaration de la variable niveau
    $niveau = "./";

    //Récupération de query string
    if(isset($_GET["id_liste"])){
        $id_liste = $_GET["id_liste"];
    }

    //******************** Sélection des infos de la liste ********************
    $strRequest = "SELECT id_liste, nom_liste, id_couleur, id_utilisateur FROM t_liste WHERE id_liste = :id_liste";

    $pdosResultat = $pdoConnexion -> prepare($strRequest);

    $pdosResultat -> bindValue("id_liste", $id_liste);

    $pdosResultat -> execute();

    $arrInfosListe = $pdosResultat -> fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Never4Get</title>
    <link rel="stylesheet" href="css/styles.css">
    <?php include($niveau . "inc/scripts/headlinks.php"); ?>
</head>
<body>
    <!--  HEADER  -->
    <?php include("inc/fragments/header.inc.php"); ?>
    <main class="contenu">
        <h1 id="contenu__titre"></h1>





    </main>

    <!--  FOOTER  -->
    <?php include("inc/fragments/footer.inc.php"); ?>

    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>

    <script>window.jQuery || document.write('<script src="node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>
<!--    <script src="js/validationsMandatB.js"></script>-->
    <script>
        $('body').addClass('js');
        /**
         * Initialiser les modules JavaScript ici: menu, accordéon...
         */
        // $(document).ready(validationsMandatX.initialiser.bind(validationsMandatX));
    </script>
</body>
</html>