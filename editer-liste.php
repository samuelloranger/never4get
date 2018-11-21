<?php
    //*******DÉCLARATION DES VARIABLES*******/
    //Initialisation de la variable niveau
    $strNiveau = "./";
    //Initialisation de la variable du tableay des échéances à venir
    $arrCouleurs=array();
    //Initialisation de la variable de l'utilisateur (HARD CODER POUR PROTOTYPE)
    $strUtilisateur=1;

    //Inclusion de la config liant aux BD
    include ($strNiveau.'inc/scripts/config.inc.php');

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Projet TOFU</title>
    <!--URL de base pour la navigation -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include($strNiveau.'inc/fragments/header.inc.php'); ?>
    <!--http://webaim.org/techniques/skipnav/-->
    <a href="#contenu">Allez au contenu</a>

    <main>
        <noscript>
            <p>Le JavaScript n'est pas activé dans votre navigateur. Nous vous recommandons de l'activer afin d'améliorer votre expérience utilisateur.</p>
        </noscript>
    </main>




    <?php include($strNiveau.'inc/fragments/footer.inc.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>

    <script>window.jQuery || document.write('<script src="node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>

    <script>
    $('body').addClass('js');
    $(document).ready(function()
    {
        /**
         *Initialiser les modules JavaScript ici: menu, accordéon...
         */
    });
    </script>
</body>
</html>