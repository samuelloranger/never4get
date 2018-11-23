<?php
    //******************** Déclarations des variables ********************
    // Inclusion du fichier de configuration
     include($niveau . 'inc/scripts/config.inc.php');

    //Déclaration de la variable niveau
    $niveau = "./";

    //Déclaration de la variable d'éxécution
    $strCodeOperation = "";

    //Déclaration du code d'erreur
    $strCodeErreur = "00000";

    //Définit l'année de départ
    $dateAjd = new DateTime();
    $anneeAjd = $dateAjd -> format('Y');

    //Tableau des mois pour affichage
    $arrMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

    //Récupération de query string
    if(isset($_GET["id_item"])){
        $strIdItem = $_GET["id_item"];
    }

    //******************** Gestion des messages d'erreur ********************
    $strFichierJson = file_get_contents($niveau. "js/objJSONMessages.json");
    $jsonMessageErreur = json_decode($strFichierJson);

    $arrChampErreur = array();

    //Liste des message d'erreur à afficher
    $arrMessageErreur = array();
    $arrMessageErreur["nom_item"] = "";
    $arrMessageErreur["echeance"] = "";

    //******************** Fonction utilistaires ********************
    //Fonction utilitaire pour l'affichage de la liste déroulante
    function ecrireSelected($valeurOption, $nomSelection){
        $strSelection = "";
        global $arrParticipant;
        if($valeurOption == $arrParticipant[$nomSelection]){
            $strSelection = 'selected="selected"';
        }
        return $strSelection;
    }

    //******************** Affichage des infos de l'item ********************
    $strRequeteInfosItem = "SELECT id_item, nom_item, DAY(echeance) AS jour, MONTH(echeance) AS mois , YEAR(echeance) AS annee,  HOUR(echeance) AS heure,  MINUTE(echeance) AS minute, t_item.id_liste, nom_liste
                                        FROM t_item
                                        INNER JOIN t_liste ON t_liste.id_liste = t_item.id_liste
                                        WHERE id_item = :id_item";

    //Préparation de la requête
    $pdosResultatInfosItem = $pdoConnexion -> prepare($strRequeteInfosItem);

    //Insertion des valeurs de querystring dans la requête
    $pdosResultatInfosItem -> bindValue("id_item", $strIdItem);

    //Éxécution de la requête
    $pdosResultatInfosItem -> execute();

    $arrInfosItem = $pdosResultatInfosItem -> fetch();

    $arrInfosItem["minute"] = "-1";

    //******************** Ajouter/Modifier la date d'échéance ********************
    if (isset($_GET["ajouterEcheance"])) {
        /**
         * Pour info seulement :
         * Lorsque le formulaire est envoyé, on fera des validations sur l'ensemble du formulaire
         * et seulement si tout est correct, on ajoute l'occurrence dans la BD.
         */
        $arrInfosItem["id_item"] = $_GET["id_item"];
        $arrInfosItem["id_liste"] = $_GET["id_liste"];
        $arrInfosItem["nom_item"] = $_GET["nom_item"];
        $arrInfosItem["jour"] =  $_GET["jour"];
        $arrInfosItem["mois"] =  $_GET["mois"];
        $arrInfosItem["annee"] =  $_GET["annee"];
        $arrInfosItem["heure"] =  $_GET["heure"];
        $arrInfosItem["minute"] =  $_GET["minute"];
        if($arrInfosItem["annee"] == 0 AND $arrInfosItem["mois"] == 0 AND $arrInfosItem["jour"] == 0 AND $arrInfosItem["heure"] == 0 AND $arrInfosItem["minute"] == -1){
            $arrInfosItem["echeance"] = NULL;
        }
        else{
            $arrInfosItem["echeance"] = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " " . $arrInfosItem["heure"] . ":" . $arrInfosItem["minute"];
        }

        //Validation
        if(!preg_match("/^[a-zA-Zà-ÿ0-9 \'\- #]{1,55}$/", $arrInfosItem["nom_item"])){
            $strCodeErreur = "-1";
            array_push($arrChampErreur, "nom");
        }

        if( $arrInfosItem["echeance"] != null){
            if(checkdate(intval($_GET["mois"]), intval($_GET["jour"]), intval($_GET["annee"])) == false){
                $strCodeErreur = "-1";
            }
        }

        if($strCodeErreur == "00000"){
            $strRequeteUpdateInfosItem = "UPDATE t_item SET nom_item = :nom_item, echeance = :echeance WHERE id_item = :id_item";

            $pdosResultatModifierItem =$pdoConnexion -> prepare($strRequeteUpdateInfosItem);

            $pdosResultatModifierItem -> bindValue("id_item", $arrInfosItem["id_item"]);
            $pdosResultatModifierItem -> bindValue("nom_item", $arrInfosItem["nom_item"]);
            $pdosResultatModifierItem -> bindValue("echeance", $arrInfosItem["echeance"]);

            $pdosResultatModifierItem -> execute();
        }
    }

// *************************** Liste des listes **********************
    $strRequeteListes = "SELECT id_liste, nom_liste FROM t_liste";

    $pdosResultatListes = $pdoConnexion -> query($strRequeteListes);

    $arrListes = array();
    for($intCtr = 0; $ligne = $pdosResultatListes -> fetch(); $intCtr++){
        $arrListes[$intCtr]["id_liste"] = $ligne["id_liste"];
        $arrListes[$intCtr]["nom_liste"] = $ligne["nom_liste"];
    }

    $pdosResultatListes -> closeCursor();
// *************************** Gestion des messages d'erreur **********************
    if($strCodeErreur != "00000"){
        for($intCtr = 0; $intCtr < count($arrChampErreur); $intCtr++){
            $champ = $arrChampErreur[$intCtr];
//            $arrMessageErreur[$champ] = $jsonMessageErreur -> {$champ};
        }
    }
    else{
        if(isset($_GET["ajouterEcheance"])){
            header("Location:" . $niveau . "consulter-liste.php?id_liste=" . $arrInfosItem["id_liste"] . "&btnOperation=updateItem");
            //echo ("Location:" . $niveau . "consulter-liste.php?id_liste=" . $arrInfosItem["id_liste"] . "&strCodeOperation=update");
        }
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
    <noscript>
        <p>Le JavaScript n'est pas activé dans votre navigateur. Nous vous recommandons de l'activer afin d'améliorer votre expérience utilisateur.</p>
    </noscript>
    <?php include($niveau . "inc/fragments/header.inc.php"); ?>
    <main class="conteneur">
        <aside class="sidenav">
                <ul class="sidenav__liste">
                    <li class="sidenav__liste__item"><a class="sidenav__liste__item__lien fi flaticon-home" href="index.php">Accueil</a></li>
                    <li class="sidenav__liste__item"><a class="sidenav__liste__item__lien fi flaticon-list" href="">Ajouter une liste</a></li>
                    <?php for($intCtr = 0; $intCtr < count($arrListes); $intCtr++){?>
                        <li class="sidenav__liste__item<?php if($arrInfosItem["id_liste"] == $arrListes[$intCtr]["id_liste"]) { echo "__active"; } ?>"><a  class="sidenav__liste__item__lien" href="consulter-liste.php?id_liste=<?php echo  $arrListes[$intCtr]["id_liste"] ?>"><?php echo $arrListes[$intCtr]["nom_liste"]; ?></a></li>
                    <?php } ?>
                </ul>
        </aside>

        <div class="contenu">
            <h1 id="contenu__titre">Éditer un item</h1>
            <h2 class="contenu__liste">Liste: <span><?php echo $arrInfosItem["nom_liste"]?></span></h2>

            <form class="formulaire" id="formDemoValidation" action="editer-item.php">
                <input type="hidden" name="id_item" value="<?php echo $arrInfosItem["id_item"]; ?>">
                <input type="hidden" name="id_liste" value="<?php echo $arrInfosItem["id_liste"]; ?>">

                <div class="formulaire__conteneurChamp">
                    <label class="conteneurChamp__nomItem__label" for="nomListe">Nom de l'item: </label>
                    <input class="conteneurChamp__nomItem__input" type="text" name="nom_item" id="nom_item" pattern="[a-zA-ZÀ-ÿ1-9 -'#]{1,55}" value="<?php echo $arrInfosItem["nom_item"]?>" required>
                    <p class="erreur"><?php echo $arrMessageErreur["nom_item"]?></p>
                </div>

                <p class="formulaire__dateEcheanceTitre">Date d'échéance (facultatif) </p>
                <fieldset class="formulaire__conteneurDate">
                    <div class="date">
                        <label for="jour" class="screen-reader-only">Jour</label>
                        <select name="jour" id="jour">
                            <option value="0">Jour</option>
                            <?php for($intCtr = 1; $intCtr <= 31; $intCtr++){?>
                                <option value="<?php echo $intCtr; ?>" <?php if($arrInfosItem["jour"] == $intCtr){ echo "selected='selected'";}?>><?php echo $intCtr; ?></option>
                            <?php } ?>
                        </select>
                        <label for="mois" class="screen-reader-only">Mois</label>
                        <select name="mois" id="mois">
                            <option value="0">Mois</option>
                            <?php for($intCtr = 1; $intCtr < count($arrMois)+1; $intCtr++){?>
                                <option value="<?php echo $intCtr;?>" <?php if($arrInfosItem["mois"] == $intCtr){ echo "selected='selected'";}?>><?php echo $arrMois[$intCtr-1];?></option>
                            <?php }?>
                        </select>
                        <label for="annee" class="screen-reader-only">Année</label>
                        <select name="annee" id="annee">
                            <option value="0">Année</option>
                            <?php for($intCtr = $anneeAjd; $intCtr <= $anneeAjd+5; $intCtr++){ ?>
                                <option value="<?php echo $intCtr?>"  <?php if($arrInfosItem["annee"] == $intCtr){ echo "selected='selected'";}?>><?php echo $intCtr?></option>
                            <?php } ?>
                        </select>

                        <label>à</label>
                        <select name="heure" id="heure">
                            <option value="0">Heure</option>
                            <?php for($intCtr = 1; $intCtr <= 24; $intCtr++){?>
                                <option value="<?php echo $intCtr?>" <?php if($arrInfosItem["heure"] == $intCtr){ echo "selected='selected'";}?>><?php if($intCtr <= 9){ echo "0" . $intCtr; } else { echo $intCtr; }?></option>
                            <?php } ?>
                        </select>

                        <label>:</label>
                        <select name="minute" id="minute">
                            <option value="-1">Minute</option>
                            <?php for($intCtr = 0; $intCtr <= 59; $intCtr++){?>
                                <option value="<?php echo $intCtr?>" <?php if($arrInfosItem["minute"] == $intCtr){ echo "selected='selected'";}?>><?php if($intCtr <= 9){ echo "0" . $intCtr; } else { echo $intCtr; }?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <p class="erreur"><?php echo $arrMessageErreur["echeance"]?></p>
                </fieldset>
                <p>
                    <button name="ajouterEcheance" value="ajouterEcheance">Modifier l'item</button>
                </p>
            </form>
        </div>
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
        $(document).ready(validationsMandatB.initialiser.bind(validationsMandatB));
    </script>

</body>
</html>