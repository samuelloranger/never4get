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
    $strRequeteInfosItem = "SELECT id_item, nom_item, echeance, t_item.id_liste, nom_liste
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

    $arrInfosItem["date"] = substr($arrInfosItem["echeance"], 0, 10);
    $arrInfosItem["heure"] = substr($arrInfosItem["echeance"], 11, 5);

    //******************** Ajouter/Modifier la date d'échéance ********************
    if ($strCodeOperation == "modifier") {
        //On va chercher les valeurs dans la queryString
        $arrInfosItem["id_item"] = $_GET["id_item"];
        $arrInfosItem["id_liste"] = $_GET["id_liste"];
        $arrInfosItem["nom_item"] = $_GET["nom_item"];
        $arrInfosItem["date"] =  $_GET["date"];
        $arrInfosItem["time"] =  $_GET["time"];
        $arrInfosItem["annee"] = substr($arrInfosItem["date"], 0, 4);
        $arrInfosItem["mois"] = substr($arrInfosItem["date"], 5, 2);
        $arrInfosItem["jour"] = substr($arrInfosItem["date"], 8, 2);
        $arrInfosItem["heure"] = substr($arrInfosItem["time"], 0, 2);
        $arrInfosItem["minute"] = substr($arrInfosItem["time"], 3, 2);

        //Si la date est entrée correctement
        if($arrInfosItem["annee"] != 0 AND $arrInfosItem["mois"] != 0 AND $arrInfosItem["jour"] != 0){
                //Vérifie si la date est correcte
                if(checkdate($arrInfosItem["mois"], $arrInfosItem["jour"], $arrInfosItem["annee"]) == true){
                    //On entre la date saisie dans un format (Y-m-d H:m)
                    $dateSaisie = $arrInfosItem["annee"] . "-" . $arrInfosItem["mois"] . "-" . $arrInfosItem["jour"];

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
    <?php include($strNiveau . "inc/fragments/header.inc.php"); ?>
    <noscript>
        <p>Le JavaScript n'est pas activé dans votre navigateur. Nous vous recommandons de l'activer afin d'améliorer votre expérience utilisateur.</p>
    </noscript>
    <main class="flexgererItem conteneur">

        <?php include($strNiveau . "inc/fragments/sideNav.inc.php"); ?>

        <div class="gererItem contenu">
            <h1 class="gererItem__titre">Éditer un item</h1>

            <h2 class="gererItem__liste">Liste: <span><?php echo $arrInfosItem["nom_liste"]?></span></h2>

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
                        <label class="visuallyHidden">Date:</label>
                        <input type="date" name="date" id="date" class="date__date" value="<?php echo $arrInfosItem["date"];?>">

                        <label class="visuallyHidden">Heure:</label>
                        <input type="time" name="time" id="heure" class="date__heure" value="<?php echo $arrInfosItem["heure"];?>">
                    </div>
                </fieldset>

                <div class="conteneurBoutons">
                    <button class="btn btnOperation" name="btnModifier" value="modifier">Modifier l'item</button>
                    <a class="btn btnAnnuler" href="consulter-liste.php?id_liste=<?php echo $arrInfosItem["id_liste"]?>" id="btnAnnuler">Annuler</a>
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