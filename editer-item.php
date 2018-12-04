<?php
    //******************** DÉCLARATION DES VARIABLES ********************
    // Inclusion du fichier de configuration
     include($strNiveau . 'inc/scripts/config.inc.php');

    //Déclaration de la variable niveau
    $strNiveau = "./";

    //Déclaration de la variable d'éxécution
    $strCodeOperation = "";


   //******************** DATES ********************
    //Définit l'année de départ
    $dateAjd = new DateTime();
    //Définit l'année du moment du chargement de la date.
    $anneeAjd = $dateAjd -> format('Y');
    //Définit la date lors du chargement de la page parce qu'elle est effacée par la ligne du haut.
    $dateAjd = new DateTime();


   //******************** UTILITAIRES ********************
    //Tableau des mois pour affichage
    $arrMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

    //Récupération de query string
    if(isset($_GET["id_item"])){
        $strIdItem = $_GET["id_item"];
    }

    //Code d'opérations
    if(isset($_GET["btnModifier"])){
        $strCodeOperation = "modifier";
    }


    //******************** Gestion des messages d'erreur ********************
    //Va chercher le contenu du json dans le fichier objJSONMessages.json
    $strFichierJson = file_get_contents($strNiveau. "js/objJSONMessages.json");
    //Décode et insére le contenu du json dans la variable
    $jsonMessagesErreurs = json_decode($strFichierJson);

    //Array des champs qui sont en erreurs.
    $arrChampsErreurs = array();

    //Liste des message d'erreur à afficher
    $arrMessagesErreurs = array();
    $arrMessagesErreurs["nom_item"] = "";
    $arrMessagesErreurs["echeance"] = "";

    //Code d'opération 00000 de base -> donc pas d'erreur
    $strCodeErreur = "00000";

    //Code de date incomplète qui change à true lorsque l'utilisateur
    // entre seulement une partie de la date ou si le nom entré est vide
    $date_incomplete = false;
    $nomVide = false;

    //******************** Fonction utilitaires ********************
    //Fonction utilitaire pour l'affichage de la liste déroulante
    //@param $valeurOption: Va chercher la valeur du option
    //@param $nomSelection: Entre le nom du select
    //return: Retourne rien OU selected="selected"
    function ecrireSelected($valeurOption, $nomSelection){
        $strSelection = "";
        global $arrInfosItem;
        if($valeurOption == $arrInfosItem[$nomSelection]){
            $strSelection = 'selected="selected"';
        }
        return $strSelection;
    }


    //******************** Affichage des infos de l'item ********************
    $strRequeteInfosItem = "SELECT id_item, nom_item, DAY(echeance) AS jour, echeance, MONTH(echeance) AS mois , YEAR(echeance) AS annee,  HOUR(echeance) AS heure,  MINUTE(echeance) AS minute, t_item.id_liste, nom_liste
                                        FROM t_item
                                        INNER JOIN t_liste ON t_liste.id_liste = t_item.id_liste
                                        WHERE id_item = :id_item";

    //Préparation de la requête
    $pdosResultatInfosItem = $pdoConnexion -> prepare($strRequeteInfosItem);

    //Insertion des valeurs de querystring dans la requête
    $pdosResultatInfosItem -> bindValue("id_item", $strIdItem);

    //Éxécution de la requête
    $pdosResultatInfosItem -> execute();

    //Insére les valeurs dans le $arrInfosItem pour utilisation ultérieure
    $arrInfosItem = $pdosResultatInfosItem -> fetch();

    //Si la date n'est pas entrée, on remet les valeurs de l'heure et des minutes à -1
    if($arrInfosItem["echeance"] == null){
        $arrInfosItem["heure"] = -1;
        $arrInfosItem["minute"] = -1;
    }


    //******************** Ajouter/Modifier la date d'échéance ********************
    if ($strCodeOperation == "modifier") {
        //On va chercher les valeurs dans la queryString
        $arrInfosItem["id_item"] = $_GET["id_item"];
        $arrInfosItem["id_liste"] = $_GET["id_liste"];
        $arrInfosItem["nom_item"] = $_GET["nom_item"];
        $arrInfosItem["jour"] =  $_GET["jour"];
        $arrInfosItem["mois"] =  $_GET["mois"];
        $arrInfosItem["annee"] =  $_GET["annee"];
        $arrInfosItem["heure"] =  $_GET["heure"];
        $arrInfosItem["minute"] =  $_GET["minute"];

        //Si la date est entrée correctement
        if($arrInfosItem["annee"] != 0 AND $arrInfosItem["mois"] != 0 AND $arrInfosItem["jour"] != 0){
                //Vérifie si la date est correcte
                if(checkdate($arrInfosItem["mois"], $arrInfosItem["jour"], $arrInfosItem["annee"]) == true){
                    //On entre la date saisie dans un format (Y-m-d H:m)
                    $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " " . $arrInfosItem["heure"] . ":" . $arrInfosItem["minute"];

                    //On créer un objet date avec la date saisie
                    $newDateEcheance = new DateTime($dateSaisie);

                    //Si la date est correcte, on vérifie si la date est antérieure
                    if($newDateEcheance > $dateAjd){
                        //Si l'heure et les minutes sont définies
                        if($arrInfosItem["heure"] != -1 AND $arrInfosItem["minute"] != -1){
                            $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " " . $arrInfosItem["heure"] . ":" . $arrInfosItem["minute"];
                        }
                        else {
                            //Si l'heure et les minutes ne sont pas settés
                            if($arrInfosItem["heure"] == -1 AND $arrInfosItem["minute"] == -1){
                                //On créer le format de la date dans une variable
                                $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " 0:0";
                            }
                            else{
                                //Si l'heure est définie et que les minutes ne le sont pas
                                if ($arrInfosItem["heure"] != -1 AND $arrInfosItem["minute"] == -1) {
                                    $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " " . $arrInfosItem["heure"] . ":0";
                                }
                                //Si l'heure n'est pas définie et que les minutes le sont
                                else {
                                    $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " 0:" . $arrInfosItem["minute"];
                                }
                            }
                        }
                        $arrInfosItem["echeance"] = $dateSaisie;
                    }
                    else{
                        $strCodeErreur = "-1";
                        array_push($arrChampsErreurs, "echeance");
                        var_dump("date trop petite");
                    }
                }
                //Si la date est entrée n'est pas valide
                else{
                    if($arrInfosItem["annee"] != 0 OR $arrInfosItem["mois"] != 0 OR $arrInfosItem["jour"] != 0)
                        $strCodeErreur = "-1";
                        array_push($arrChampsErreurs, "echeance");
                    }
                }
        //Si la date n'est pas entrée correctement
        else{
            //Si la date entrée n'est tout simplement pas entré, la date dans le arr passe
            if($arrInfosItem["annee"] == 0 AND $arrInfosItem["mois"] == 0 AND $arrInfosItem["jour"] == 0 ){
                $arrInfosItem["echeance"] = NULL;
            }
            //Si la est entrée n'est pas complète
            else{
                $strCodeErreur = "-1";
                array_push($arrChampsErreurs, "echeance");
                $date_incomplete = true;

            }
        }

        //Validation du nom de l'item avec un regex
        if(!preg_match("/^[a-zA-Zà-ÿ0-9 \'\- #]{1,55}$/", $arrInfosItem["nom_item"])){
            $strCodeErreur = "-1";
            array_push($arrChampErreur, "nomItem");
        }

        //Si le nom est vide
        if($arrInfosItem["nom_item"] == ""){
            $nomVide = true;
        }

        //Si le code d'erreur n'a pas été changé on passe dans la requête
        if($strCodeErreur == "00000"){
            //Déclaration la requête
            $strRequeteUpdateInfosItem = "UPDATE t_item SET nom_item = :nom_item, echeance = :echeance WHERE id_item = :id_item";

            //Préparation de la requête
            $pdosResultatModifierItem = $pdoConnexion -> prepare($strRequeteUpdateInfosItem);

            //Attachement des valeurs de la queryString à la requête
            $pdosResultatModifierItem -> bindValue("id_item", $arrInfosItem["id_item"]);
            $pdosResultatModifierItem -> bindValue("nom_item", $arrInfosItem["nom_item"]);
            $pdosResultatModifierItem -> bindValue("echeance", $arrInfosItem["echeance"]);

            //Éxécution de la requête
            $pdosResultatModifierItem -> execute();
        }
    }

    // *************************** Gestion des messages d'erreur **********************
    //Si le code d'erreur a été changé = il y a au moins une erreur
    if($strCodeErreur != "00000"){
        //On boucle pour trouver toutes les erreurs
        for($intCtr = 0; $intCtr < count($arrChampsErreurs); $intCtr++){
            //Définition du champ en erreur
            $champ = $arrChampsErreurs[$intCtr];

            //Si c'est la date est mal entrée ou si le nom n'est pas entré
            if($date_incomplete == true OR $nomVide == true){
                $arrMessagesErreurs[$champ] = $jsonMessagesErreurs -> {$champ} -> {"erreurs"} -> {"vide"};
            }
            else{
                $arrMessagesErreurs[$champ] = $jsonMessagesErreurs -> {$champ} -> {"erreurs"} -> {"motif"};
            }
        }
    }
    //S'il y n'y pas d'erreur
    else{
        //Si le code d'opération est modifier,
        if($strCodeOperation == "modifier"){
            //Redirection vers consulter-liste
            header("Location:" . $strNiveau . "consulter-liste.php?id_liste=" . $arrInfosItem["id_liste"] ."&btnOperation=modifier");
            //Pour tests seulement
            //var_dump("Location:" . $strNiveau . "consulter-liste.php?id_liste=" . $arrInfosItem["id_liste"] ."&strCodeOperation=modifier");
        }
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Editer <?php echo $arrInfosItem["nom_item"]; ?>  - Never4Get</title>
    <link rel="stylesheet" href="css/styles.css">
    <?php include($strNiveau . "inc/scripts/headlinks.php"); ?>
</head>
<body>
    <noscript>
        <p>Le JavaScript n'est pas activé dans votre navigateur. Nous vous recommandons de l'activer afin d'améliorer votre expérience utilisateur.</p>
    </noscript>
    <?php include($strNiveau . "inc/fragments/header.inc.php"); ?>

    <main class="flexEditerItem conteneur">

        <?php include($strNiveau . "inc/fragments/sideNav.inc.php"); ?>

        <div class="editerItem contenu">
            <h1 class="editerItem__titre">Éditer un item</h1>

            <h2 class="editerItem__liste">Liste: <span><?php echo $arrInfosItem["nom_liste"]?></span></h2>

            <form class="formulaire" id="formDemoValidation" action="editer-item.php">
                <input type="hidden" name="id_item" value="<?php echo $arrInfosItem["id_item"]; ?>">
                <input type="hidden" name="id_liste" value="<?php echo $arrInfosItem["id_liste"]; ?>">

                <div class="formulaire__conteneurChamp">
                    <label class="label" for="nom_item">Nom de l'item: </label>
                    <input class="input" type="text" name="nom_item" id="nom_item" pattern="[a-zA-ZÀ-ÿ1-9 -'#]{1,55}" value="<?php echo $arrInfosItem["nom_item"]?>" required>
                    <p class="erreur"><?php echo $arrMessagesErreurs["nom_item"]?></p>
                </div>

                <p class="formulaire__dateEcheanceTitre">Date d'échéance (facultatif)</p>
                <p class="erreur"><?php echo $arrMessagesErreurs["echeance"]?></p>
                <fieldset class="formulaire__conteneurDate">

                    <div class="date__conteneurSelectDate">
                        <label for="jour" class="screen-reader-only">Jour</label>
                        <select name="jour" id="jour" class="date__jour">
                            <option value="0">Jour</option>
                            <?php for($intCtr = 1; $intCtr <= 31; $intCtr++){?>
                                <option value="<?php echo $intCtr; ?>" <?php if($arrInfosItem["jour"] == $intCtr){ echo "selected='selected'";}?>><?php echo $intCtr; ?></option>
                            <?php } ?>
                        </select>
                        <label for="mois" class="screen-reader-only">Mois</label>
                        <select name="mois" id="mois" class="date__mois">
                            <option value="0">Mois</option>
                            <?php for($intCtr = 1; $intCtr < count($arrMois)+1; $intCtr++){?>
                                <option value="<?php echo $intCtr;?>" <?php if($arrInfosItem["mois"] == $intCtr){ echo "selected='selected'";}?>><?php echo $arrMois[$intCtr-1];?></option>
                            <?php }?>
                        </select>
                        <label for="annee" class="screen-reader-only">Année</label>
                        <select name="annee" id="annee" class="date__annee">
                            <option value="0">Année</option>
                            <?php for($intCtr = $anneeAjd; $intCtr <= $anneeAjd+5; $intCtr++){ ?>
                                <option value="<?php echo $intCtr?>"  <?php if($arrInfosItem["annee"] == $intCtr){ echo "selected='selected'";}?>><?php echo $intCtr?></option>
                            <?php } ?>
                        </select>

                        <label class="date__heure--label">à</label>
                        <select name="heure" id="heure" class="date__heure">
                            <option value="-1">Heure</option>
                            <?php for($intCtr = 0; $intCtr <= 24; $intCtr++){?>
                                <option value="<?php echo $intCtr?>" <?php if($arrInfosItem["heure"] == $intCtr){ echo "selected='selected'";}?>><?php if($intCtr <= 9){ echo "0" . $intCtr; } else { echo $intCtr; }?></option>
                            <?php } ?>
                        </select>

                        <label class="date__minute--label">:</label>
                        <select name="minute" id="minute" class="date__minute">
                            <option value="-1">Minute</option>
                            <?php for($intCtr = 0; $intCtr <= 59; $intCtr++){?>
                                <option value="<?php echo $intCtr?>" <?php if($arrInfosItem["minute"] == $intCtr){ echo "selected='selected'";}?>><?php if($intCtr <= 9){ echo "0" . $intCtr; } else { echo $intCtr; }?></option>
                            <?php } ?>
                        </select>
                    </div>
                </fieldset>

                <div class="conteneurBoutons">
                    <button class="btnModifier" name="btnModifier" value="modifier">Modifier l'item</button>
                    <a href="consulter-liste.php?id_liste=<?php echo $arrInfosItem["id_liste"]?>" class="btnAnnuler" id="btnAnnuler">Annuler</a>
                </div>
            </form>
        </div>
    </main>

    <?php include($strNiveau . "inc/fragments/footer.inc.php"); ?>

    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>

    <script>window.jQuery || document.write('<script src="node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>
    <script src="js/validationsMandatB.js"></script>
    <script src="js/menu.js"></script>
    <script>
        var niveau = "<?php echo $strNiveau; ?>";

        $('body').addClass('js');
        /**
         * Initialiser les modules JavaScript ici: menu, accordéon...
         */
        $(document).ready(validationsMandatB.initialiser.bind(validationsMandatB));

        $(document).ready(menu.initialiser.bind(menu));
    </script>

</body>
</html>