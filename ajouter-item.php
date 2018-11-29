<?php
//******************** Déclarations des variables ********************
// Inclusion du fichier de configuration
include($strNiveau . 'inc/scripts/config.inc.php');

//Déclaration de la variable niveau
$strNiveau = "./";

//Déclaration de la variable d'éxécution
$strCodeOperation = "";

//Déclaration du code d'erreur

//Définit l'année de départ
$dateAjd = new DateTime();
$anneeAjd = $dateAjd -> format('Y');
$dateAjd = new DateTime();
$dateAjd -> format("Y-m-d H:i:s");

//Tableau des mois pour affichage
$arrMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

//Code de date
$date_incomplete = false;

//Récupération de query string
if(isset($_GET["id_liste"])){
    $strIdListe = $_GET["id_liste"];
}

//Code d'opérations
if(isset($_GET["btnAjouter"])){
    $strCodeOperation = "ajouter";
}

//******************** Gestion des messages d'erreur ********************
$strFichierJson = file_get_contents($strNiveau. "js/objJSONMessages.json");
$jsonMessagesErreurs = json_decode($strFichierJson);

$arrChampsErreurs = array();

//Liste des message d'erreur à afficher
$arrMessagesErreurs = array();
$arrMessagesErreurs["nom_item"] = "";
$arrMessagesErreurs["echeance"] = "";

//Code d'opération 00000 de base -> donc pas d'erreur
$strCodeErreur = "00000";

//Message di


//******************** Fonction utilitaires ********************
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
$strRequeteInfoListe = "SELECT id_liste, nom_liste
                                        FROM t_liste
                                        WHERE id_liste = :id_liste";

//Préparation de la requête
$pdosResultatInfosListe = $pdoConnexion -> prepare($strRequeteInfoListe);

//Insertion des valeurs de querystring dans la requête
$pdosResultatInfosListe -> bindValue("id_liste", $strIdListe);

//Éxécution de la requête
$pdosResultatInfosListe -> execute();

$arrInfosListe = $pdosResultatInfosListe -> fetch();

//******************** Ajouter/Modifier la date d'échéance ********************
if ($strCodeOperation == "ajouter") {
    /**
     * Pour info seulement :
     * Lorsque le formulaire est envoyé, on fera des validations sur l'ensemble du formulaire
     * et seulement si tout est correct, on ajoute l'occurrence dans la BD.
     */
    $arrInfosItem["id_liste"] = $_GET["id_liste"];
    $arrInfosItem["nom_item"] = $_GET["nom_item"];
    $arrInfosItem["jour"] =  $_GET["jour"];
    $arrInfosItem["mois"] =  $_GET["mois"];
    $arrInfosItem["annee"] =  $_GET["annee"];
    $arrInfosItem["heure"] =  $_GET["heure"];
    $arrInfosItem["minute"] =  $_GET["minute"];

    //Si la date est pas rentrée correctement
    if($arrInfosItem["annee"] != 0 AND $arrInfosItem["mois"] != 0 AND $arrInfosItem["jour"] != 0){
        //Vérifie si la date est correcte
        if(checkdate($arrInfosItem["mois"], $arrInfosItem["jour"], $arrInfosItem["annee"]) == true){
            $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " " . $arrInfosItem["heure"] . ":" . $arrInfosItem["minute"];

            //Si la date est correcte, on vérifie si la date est antérieure
            if($dateSaisie < $dateAjd){
                //Si l'heure ET les minutes sont définies
                if($arrInfosItem["heure"] != 0 AND $arrInfosItem["minute"] != -1){
                    $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " " . $arrInfosItem["heure"] . ":" . $arrInfosItem["minute"];
                }
                else {
                    //Si l'heure est définie et que les minutes ne le sont pas
                    if ($arrInfosItem["heure"] != 0 AND $arrInfosItem["minute"] == -1) {
                        $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " " . $arrInfosItem["heure"] . ":0";
                    }
                    //Si l'heure n'est pas définie et que les minutes le sont
                    else {
                        $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " 0:" . $arrInfosItem["minute"];
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
                var_dump("date pas valide");
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
            var_dump("date pas complète");

        }
    }


    //Validation
    if(!preg_match("/^[a-zA-Zà-ÿ0-9 \'\- #]{1,55}$/", $arrInfosItem["nom_item"])){
        $strCodeErreur = "-1";
        array_push($arrChampErreur, "nomItem");
    }

    if($strCodeErreur == "00000"){
        $strRequeteUpdateInfosItem = "INSERT INTO t_item (nom_item, echeance, est_complete, id_liste)
                                                     VALUES (:nom_item, :echeance, '0', :id_liste)";

        $pdosResultatAjouterItem =$pdoConnexion -> prepare($strRequeteUpdateInfosItem);

        $pdosResultatAjouterItem -> bindValue("nom_item", $arrInfosItem["nom_item"]);
        $pdosResultatAjouterItem -> bindValue("echeance", $arrInfosItem["echeance"]);
        $pdosResultatAjouterItem -> bindValue("id_liste", $arrInfosItem["id_liste"]);

        $pdosResultatAjouterItem -> execute();
    }
}

// *************************** Gestion des messages d'erreur **********************
if($strCodeErreur != "00000"){
    //Remplacer le message de confirmation par le message d'erreur
//        $strMessage = $jsonMessagesErreurs -> {$champ};
    for($intCtr = 0; $intCtr < count($arrChampsErreurs); $intCtr++){
        $champ = $arrChampsErreurs[$intCtr];
        if($date_incomplete == true){
            $arrMessagesErreurs[$champ] = $jsonMessagesErreurs -> {$champ} -> {"erreurs"} -> {"vide"};
        }
        else{
            $arrMessagesErreurs[$champ] = $jsonMessagesErreurs -> {$champ} -> {"erreurs"} -> {"motif"};
        }
    }
}
else{
    if($strCodeOperation == "ajouter"){
        header("Location:" . $strNiveau . "consulter-liste.php?id_liste=" . $arrInfosItem["id_liste"] ."&strCodeOperation=modifier");
//            var_dump("Location:" . $strNiveau . "consulter-liste.php?id_liste=" . $arrInfosItem["id_liste"] ."&strCodeOperation=modifier");
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
        <h1 class="editerItem__titre">Ajouter un item</h1>

        <h2 class="editerItem__liste">Liste: <span><?php echo $arrInfosListe["nom_liste"]?></span></h2>

        <form class="formulaire" id="formDemoValidation" action="ajouter-item.php">
            <input type="hidden" name="id_liste" value="<?php echo $arrInfosListe["id_liste"]; ?>">

            <div class="formulaire__conteneurChamp">
                <label class="label" for="nom_item">Nom de l'item: </label>
                <input class="input" type="text" name="nom_item" id="nom_item" pattern="[a-zA-ZÀ-ÿ1-9 -'#]{1,55}" value="" required>
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
                            <option value="<?php echo $intCtr; ?>"><?php echo $intCtr; ?></option>
                        <?php } ?>
                    </select>
                    <label for="mois" class="screen-reader-only">Mois</label>
                    <select name="mois" id="mois" class="date__mois">
                        <option value="0">Mois</option>
                        <?php for($intCtr = 1; $intCtr < count($arrMois)+1; $intCtr++){?>
                            <option value="<?php echo $intCtr;?>"><?php echo $arrMois[$intCtr-1];?></option>
                        <?php }?>
                    </select>
                    <label for="annee" class="screen-reader-only">Année</label>
                    <select name="annee" id="annee" class="date__annee">
                        <option value="0">Année</option>
                        <?php for($intCtr = $anneeAjd; $intCtr <= $anneeAjd+5; $intCtr++){ ?>
                            <option value="<?php echo $intCtr?>"><?php echo $intCtr?></option>
                        <?php } ?>
                    </select>

                    <label class="date__heure--label">à</label>
                    <select name="heure" id="heure" class="date__heure">
                        <option value="0">Heure</option>
                        <?php for($intCtr = 1; $intCtr <= 24; $intCtr++){?>
                            <option value="<?php echo $intCtr?>"><?php if($intCtr <= 9){ echo "0" . $intCtr; } else { echo $intCtr; }?></option>
                        <?php } ?>
                    </select>

                    <label class="date__minute--label">:</label>
                    <select name="minute" id="minute" class="date__minute">
                        <option value="-1">Minute</option>
                        <?php for($intCtr = 0; $intCtr <= 59; $intCtr++){?>
                            <option value="<?php echo $intCtr?>"><?php if($intCtr <= 9){ echo "0" . $intCtr; } else { echo $intCtr; }?></option>
                        <?php } ?>
                    </select>
                </div>
            </fieldset>

            <div class="conteneurBoutons">
                <button class="btnModifier" name="btnAjouter" value="ajouter">Ajouter l'item</button>
                <a class="btnAnnuler" id="btnAnnuler">Annuler</a>
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