<?php
//******************** Déclarations des variables ********************
// Inclusion du fichier de configuration
// include($niveau . 'inc/scripts/config.inc.php');

//Déclaration de la variable niveau
$niveau = "./";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Never4Get</title>
    <link rel="stylesheet" href="css/styles.css">
    <?php @include($niveau . "inc/scripts/headlinks.php"); ?>
</head>
<body>
    <main class="contenu">
        <h1 id="contenu__titre"></h1>
        <h2 class="contenu__liste"></h2>




    </main>
    <footer>
        <p>Tous droits réservés, 2018.</p>
    </footer>
    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>

    <script>window.jQuery || document.write('<script src="node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>
    <script src="js/validationsMandatB.js"></script>
    <script>
        $('body').addClass('js');
        /**
         * Initialiser les modules JavaScript ici: menu, accordéon...
         */
        // $(document).ready(validationsMandatX.initialiser.bind(validationsMandatX));
    </script>
</body>
</html>