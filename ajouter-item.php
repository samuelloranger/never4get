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
    if(isset($_GET["id_liste"])){
        $strIdListe = $_GET["id_liste"];
    }

    //Code d'opérations
    if(isset($_GET["btnAjouter"])){
        $strCodeOperation = "ajouter";
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
                    if($arrInfosItem["heure"] != -1 AND $arrInfosItem["minute"] != -1){
                        $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"] . " " . $arrInfosItem["heure"] . ":" . $arrInfosItem["minute"];
                    }
                    else {
                        //Si l'heure est définie et que les minutes ne le sont pas
                        if ($arrInfosItem["heure"] != -1 AND $arrInfosItem["minute"] == -1) {
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

        //Validation du nom avec un regex
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
            $strRequeteUpdateInfosItem = "INSERT INTO t_item (nom_item, echeance, est_complete, id_liste) VALUES (:nom_item, :echeance, '0', :id_liste)";

            $pdosResultatAjouterItem =$pdoConnexion -> prepare($strRequeteUpdateInfosItem);

            $pdosResultatAjouterItem -> bindValue("nom_item", $arrInfosItem["nom_item"]);
            $pdosResultatAjouterItem -> bindValue("echeance", $arrInfosItem["echeance"]);
            $pdosResultatAjouterItem -> bindValue("id_liste", $arrInfosItem["id_liste"]);

            $pdosResultatAjouterItem -> execute();
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
        if($strCodeOperation == "ajouter"){
            //Redirection vers consulter-liste
            header("Location:" . $strNiveau . "consulter-liste.php?id_liste=" . $arrInfosItem["id_liste"] ."&btnOperation=ajouter");
            //Pour tests seulement
            //var_dump("Location:" . $strNiveau . "consulter-liste.php?id_liste=" . $arrInfosItem["id_liste"] ."&strCodeOperation=ajouter");
        }
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Ajouter Item - Never4Get</title>
    <link rel="stylesheet" href="css/styles.css">
    <?php include($strNiveau . "inc/scripts/headlinks.php"); ?>
</head>
<body>
<noscript>
    <p>Le JavaScript n'est pas activé dans votre navigateur. Nous vous recommandons de l'activer afin d'améliorer votre expérience utilisateur.</p>
</noscript>
<?php include($strNiveau . "inc/fragments/header.inc.php"); ?>

    <main class="flexgererItem conteneur">

        <?php include($strNiveau . "inc/fragments/sideNav.inc.php"); ?>

        <div class="gererItem contenu">
            <h1 class="gererItem__titre">Ajouter un item</h1>

            <h2 class="gererItem__liste">Liste: <span><?php echo $arrInfosListe["nom_liste"]?></span></h2>

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
                            <option value="-1">Heure</option>
                            <?php for($intCtr = 0; $intCtr <= 24; $intCtr++){?>
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
                    <a href="consulter-liste.php?id_liste=<?php echo $arrInfosListe["id_liste"]?>" class="btnAnnuler" id="btnAnnuler">Annuler</a>
                </div>
            </form>
        </div>
    </main>

    <?php include($strNiveau . "inc/fragments/footer.inc.php"); ?>

    <?php include($strNiveau . "inc/scripts/footerLinks.inc.php"); ?>

    <script src="js/menu.js"></script>
    <script src="js/validationsMandatB.js"></script>
    <script>
        var niveau = "<?php echo $strNiveau; ?>";

        $('body').addClass('js');
        $(document).ready(validationsMandatB.initialiser.bind(validationsMandatB));
        $(document).ready(menu.initialiser.bind(menu));

    </script>
</body>
</html>