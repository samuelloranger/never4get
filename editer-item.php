<?php
    //******************** Déclarations des variables ********************
    // Inclusion du fichier de configuration
     include($niveau . 'inc/scripts/config.inc.php');

    //Déclaration de la variable niveau
    $niveau = "./";

    //Récupération de query string
    if(isset($_GET["id_item"])){
        $strIdItem = $_GET["id_item"];
    }

    //******************** Affichage des infos de l'item ********************

    //******************** Déclarations des variables ********************
    if (isset($_GET["ajouterEcheance"])) {
        /**
         * Pour info seulement :
         * Lorsque le formulaire est envoyé, on fera des validations sur l'ensemble du formulaire
         * et seulement si tout est correct, on ajoute l'occurrence dans la BD.
         */
        echo "Le formulaire a été envoyé. C'est à ce moment qu'on fait les validations côté serveur!";
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Démonstration - Validations avec jQuery</title>
    <link rel="stylesheet" href="css/styles.css">
    <?php include($niveau . "inc/scripts/headlinks.php"); ?>
</head>
<body>
    <?php include($niveau . "inc/fragments/header.inc.php"); ?>
    <main class="contenu">
        <h1 id="contenu__titre">Éditer un item</h1>
        <h2 class="contenu__liste">Liste:<span><!-- NOM DE LA LISTE  DE L'ITEM EN PHP ICI --></span></h2>

        <form id="formDemoValidation" action="editer-item.php">
            <div class="conteneurChamp">
                <label class="conteneurChamp__nomItem__label" for="nomListe">Nom de l'item: </label>
                <input class="conteneurChamp__nomItem__input" type="text" name="nomListe" id="nomListe" pattern="[a-zA-ZÀ-ÿ1-9 -'#]{1,55}" required>
                <p class="erreur"></p>
            </div>

            <fieldset class="conteneurChamp">
                <legend>Date d'échéance (facultatif) <div  class="cacherDateEchance__button" ><button class="cacherDateEchance__button" id="cacherDateEchance"></button></div></legend>

                <div class="date">
                    <label for="jour" class="screen-reader-only">Jour</label>
                    <select name="jour" id="jour">
                        <option value="0" selected>Jour</option>
                        <?php for($intCtr = 1; $intCtr <= 31; $intCtr++){?>
                            <option value="<?php echo $intCtr; ?>"><?php echo $intCtr; ?></option>
                        <?php } ?>
                    </select>
                    <label for="mois" class="screen-reader-only">Mois</label>
                    <select name="mois" id="mois">
                        <option value="null" selected>Mois</option>
                        <option value="1">Janvier</option>
                        <option value="2">Février</option>
                        <option value="3">Mars</option>
                        <option value="4">Avril</option>
                        <option value="5">Mai</option>
                    </select>
                    <label for="annee" class="screen-reader-only">Année</label>
                    <select name="annee" id="annee">
                        <option value="null" selected>Année</option>
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                        <option value="2020">2020</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                    </select>
                </div>
                <p class="erreur"></p>
            </fieldset>
            <p>
                <button name="ajouterEcheance">Ajouter l'échéance</button>
            </p>
        </form>
    </main>

    <?php include($niveau . "inc/fragments/footer.inc.php"); ?>

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
        $(document).ready(validationsMandatX.initialiser.bind(validationsMandatX));
    </script>

</body>
</html>