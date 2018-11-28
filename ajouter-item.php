<?php
//******************** Déclarations des variables ********************
// Inclusion du fichier de configuration
include($strNiveau . 'inc/scripts/config.inc.php');

//Déclaration de la variable niveau
$strNiveau = "./";

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
if(isset($_GET["id_liste"])){
    $strIdListe = $_GET["id_liste"];
}

//******************** Gestion des messages d'erreur ********************
$strFichierJson = file_get_contents($strNiveau. "js/objJSONMessages.json");
$jsonMessageErreur = json_decode($strFichierJson);

$arrChampErreur = array();

//Liste des message d'erreur à afficher
$arrMessageErreur = array();
$arrMessageErreur["nom_item"] = "";
$arrMessageErreur["echeance"] = "";

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
if (isset($_GET["ajouterEcheance"])) {
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
    if($arrInfosItem["annee"] == "0" AND $arrInfosItem["mois"] == "0" AND $arrInfosItem["jour"] == "0" AND $arrInfosItem["heure"] == "0" AND $arrInfosItem["minute"] == "-1"){
        $arrInfosItem["echeance"] = null;
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
    for($intCtr = 0; $intCtr < count($arrChampErreur); $intCtr++){
        $champ = $arrChampErreur[$intCtr];
//            $arrMessageErreur[$champ] = $jsonMessageErreur -> {$champ};
    }
}
else{
    if(isset($_GET["ajouterEcheance"])){
        header("Location:" . $strNiveau . "consulter-liste.php?id_liste=" . $arrInfosItem["id_liste"] . "&btnOperation=updateItem");
        //echo ("Location:" . $strNiveau . "consulter-liste.php?id_liste=" . $arrInfosItem["id_liste"] . "&strCodeOperation=update");
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

    <form  class="formulaire" id="formDemoValidation" action="ajouter-item.php">
        <input type="hidden" name="id_liste" value="<?php echo $strIdListe; ?>">

        <div class="formulaire__conteneurChamp">
            <label class="label" for="nomListe">Nom de l'item: </label>
            <input class="input" type="text" name="nom_item" id="nom_item" pattern="[a-zA-ZÀ-ÿ1-9 -'#]{1,55}" required>
            <p class="erreur"><?php echo $arrMessageErreur["nom_item"]?></p>
        </div>

        <p class="formulaire__dateEcheanceTitre">Date d'échéance (facultatif)</p>
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
            <p class="erreur"><?php echo $arrMessageErreur["echeance"]?></p>
        </fieldset>
        <div class="conteneurBoutons">
            <button class="btnModifier" name="ajouterEcheance" value="ajouterEcheance">Ajouter l'item</button>
            <a class="btnAnnuler" href="consulter-liste.php?id_liste=<?php echo $arrInfosListe["id_liste"]?>" name="ajouterEcheance" >Annuler</a>
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
    $('body').addClass('js');
    /**
     * Initialiser les modules JavaScript ici: menu, accordéon...
     */
    $(document).ready(validationsMandatB.initialiser.bind(validationsMandatB));

    $(document).ready(menu.initialiser.bind(menu));
</script>

</body>
</html>