<?php
//******************** Déclarations des variables **********
// Inclusion du fichier de configuration
// include($niveau . 'inc/scripts/config.inc.php');

//Déclaration de la variable niveau
$niveau = "./";


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
    <?php @include($niveau . "inc/scripts/headlinks.php"); ?>
</head>
<body>
    <main>
        <h1 id="contenu">Ajout d'une échéance</h1>
        <h2>Liste de choses à faire pour le cégep</h2>
        <form id="formDemoValidation" action="editer-item.php">

            <div class="conteneurChamp">
                <label for="tache">Tâche </label>
                <input type="text" name="tache" id="tache" pattern="[a-zA-ZÀ-ÿ1-9 -'#]{1,55}" required>
                <p class="erreur"></p>
            </div>

            <fieldset class="conteneurChamp">
                <legend>Cours</legend>
                <ul>
                    <li><input type="radio" name="cours" id="video" required><label for="video">Vidéo</label></li>
                    <li><input type="radio" name="cours" id="conception"><label for="conception">Conception</label></li>
                    <li><input type="radio" name="cours" id="realisation"><label for="realisation">Réalisation</label></li>
                </ul>
                <p class="erreur"></p>
            </fieldset>

            <fieldset class="conteneurChamp">
                <legend>Date d'échéance (facultatif)</legend>
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
        $(document).ready(validationsMandatX.initialiser.bind(validationsMandatX));
    </script>
</body>
</html>