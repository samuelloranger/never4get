<?php
    //******************** Déclarations des variables ********************
    // Inclusion du fichier de configuration
     include($strNiveau . 'inc/scripts/config.inc.php');

    //Déclaration de la variable niveau
    $strNiveau = "./";

    $arrMois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");

    // Code d'opération à écécuter sur la base de donnée
    $strCodeOperation = "";

    //Récupération de query string
    if(isset($_GET["id_liste"])){
        $id_liste = $_GET["id_liste"];
    }

    //Code d'opération
    if(isset($_GET["btnOperation"])){
        //Définie le code de l'opération
        switch($_GET["btnOperation"]){
            case "supprimer":
                $strCodeOperation = "supprimer";
                break;
            case "complete":
                $strCodeOperation = "modifierComplete";
                break;
            case "updateItem":
                $strCodeOperation = "updateItem";
                break;
        }
    }

    //******************** Suspression d'un item d'une liste ********************
    //Si le code d'opération est supprimer
    if($strCodeOperation == "supprimer"){
        //On va chercher le code de l'item dans la query string
        $idItemSupprimer = $_GET["id_item"];

        //Définition de la requête SQL
        $strRequeteSupprimer = "DELETE FROM t_item WHERE id_item = :idItemSupprimer";

        //Préparation de la requête
        $pdosResultatSupprimer = $pdoConnexion -> prepare($strRequeteSupprimer);

        //Insertion des valeurs de querystring dans la requête
        $pdosResultatSupprimer -> bindValue("idItemSupprimer", $idItemSupprimer);

        //Éxécution de la requête
        $pdosResultatSupprimer -> execute();
    }


    //******************** Modification de "est_complete" dans la BD  ********************
    if($strCodeOperation == "modifierComplete"){
        //On va chercher le code de l'item dans la query string
        $idItemModifierEtat = $_GET["id_item"];
        $etatComplete = $_GET["est_complete"];

        if($etatComplete == 0){
            $strRequeteModifierEtat = "UPDATE t_item SET  est_complete = 1 WHERE id_item = :idItemModifierEtat";
        }
        else{
            $strRequeteModifierEtat = "UPDATE t_item SET  est_complete = 0 WHERE id_item = :idItemModifierEtat";
        }

        //Préparation de la requête
        $pdosResultatModifierEtat = $pdoConnexion -> prepare($strRequeteModifierEtat);

        //Insertion des valeurs de querystring dans la requête
        $pdosResultatModifierEtat -> bindValue("idItemModifierEtat", $idItemModifierEtat);

        //Éxécution de la requête
        $pdosResultatModifierEtat -> execute();

    }

    //******************** Sélection des infos de la liste ********************
    //Définition de la requête SQL
    $strRequeteListe = "SELECT id_liste, nom_liste, hexadecimale, id_utilisateur 
                        FROM t_liste 
                        INNER JOIN t_couleur ON t_couleur.id_couleur = t_liste.id_couleur
                        WHERE id_liste = :id_liste";

    //Préparation de la requête
    $pdosResultatListe = $pdoConnexion -> prepare($strRequeteListe);

    //Insertion des valeurs de querystring dans la requête
    $pdosResultatListe -> bindValue("id_liste", $id_liste);

    //Éxécution de la requête
    $pdosResultatListe -> execute();

    //Insertion des infos de la BD dans le array
    $arrInfosListe = $pdosResultatListe -> fetch();

    //******************** Sélection des items de la liste ********************
    //Définition de la requête
    $strRequeteItems = "SELECT id_item, nom_item, DAY(echeance) AS jour, MONTH(echeance) AS mois, YEAR(echeance) AS annee, HOUR(echeance) AS heure, MINUTE (echeance) AS minute, echeance, est_complete, t_item.id_liste 
                                 FROM t_item
                                 INNER JOIN t_liste ON t_item.id_liste = t_liste.id_liste
                                 WHERE t_item.id_liste = :id_liste";

    //Préparation de la requête
    $pdosResultatItems = $pdoConnexion -> prepare($strRequeteItems);

    //Insertion des valeurs de querystring dans la requête
    $pdosResultatItems -> bindValue("id_liste", $id_liste);

    //Éxécution de la requête
    $pdosResultatItems -> execute();

    //Définition de la requête
    $arrItemsListe = array();
    //Boucle qui insère les données de la BD
    for($intCtr = 0; $ligne = $pdosResultatItems -> fetch(); $intCtr++){
        $arrItemsListe[$intCtr]["id_item"] = $ligne["id_item"];
        $arrItemsListe[$intCtr]["nom_item"] = $ligne["nom_item"];
        $arrItemsListe[$intCtr]["jour"] = $ligne["jour"];
        $arrItemsListe[$intCtr]["mois"] = $ligne["mois"];
        $arrItemsListe[$intCtr]["annee"] = $ligne["annee"];
        $arrItemsListe[$intCtr]["heure"] = $ligne["heure"];
        $arrItemsListe[$intCtr]["minute"] = $ligne["minute"];
        $arrItemsListe[$intCtr]["echeance"] = $ligne["echeance"];
        $arrItemsListe[$intCtr]["est_complete"] = $ligne["est_complete"];
    }

    $pdosResultatItems -> closeCursor();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Never4Get</title>
    <link rel="stylesheet" href="css/styles.css">
    <?php include($strNiveau . "inc/scripts/headlinks.php"); ?>
</head>
<body>
    <!--  HEADER  -->
    <?php include("inc/fragments/header.inc.php"); ?>
    <main class="conteneur contenu">
        <h1 id="contenu__titre"><?php echo $arrInfosListe["nom_liste"];?></h1>
        <p><?php echo $arrInfosListe["hexadecimale"]; ?></p>

        <?php if($strCodeOperation == "updateItem"){ ?>
            <p>Mise à jour réussie avec succès!</p>
        <?php } ?>

        <?php
        for($intCtr = 0; $intCtr < count($arrItemsListe); $intCtr++){ ?>
            <ul>
                <li><?php echo $arrItemsListe[$intCtr]["id_item"]; ?></li>
                <li><?php echo $arrItemsListe[$intCtr]["nom_item"]; ?></li>
                <?php if($arrItemsListe[$intCtr]["echeance"] != ""){ ?>
                    <li><?php echo $arrItemsListe[$intCtr]["jour"]; ?> <?php echo $arrMois[$arrItemsListe[$intCtr]["mois"]-1]; ?> <?php echo $arrItemsListe[$intCtr]["annee"]; ?>
                            <?php if($arrItemsListe[$intCtr]["heure"] != "0"){ ?>
                                à <?php if($arrItemsListe[$intCtr]["heure"] <= "9") { echo "0" . $arrItemsListe[$intCtr]["heure"]; } else { echo $arrItemsListe[$intCtr]["heure"]; } ?>:<?php if($arrItemsListe[$intCtr]["minute"] <= "9") { echo "0" . $arrItemsListe[$intCtr]["minute"]; } else{ echo $arrItemsListe[$intCtr]["minute"]; }  ?>
                            <?php } ?>
                    </li>
                <?php } ?>
                <li><?php echo $arrItemsListe[$intCtr]["est_complete"]; ?></li>
            </ul>
            <form action="consulter-liste.php" method="GET">
                <input type="hidden" name="id_liste" value="<?php echo $arrInfosListe["id_liste"];?>"/>
                <input type="hidden" name="id_item" value="<?php echo $arrItemsListe[$intCtr]["id_item"];?>"/>
                <input type="hidden" name="est_complete" value="<?php echo $arrItemsListe[$intCtr]["est_complete"];?>"/>
                <button name="btnOperation" value="complete"><?php echo $arrItemsListe[$intCtr]["est_complete"] == "0" ? "Complété" :  "À compléter"; ?></button>
                <button name="btnOperation" value="supprimer">Supprimer</button>
                <a href="editer-item.php?id_item=<?php echo $arrItemsListe[$intCtr]["id_item"];?>">Éditer l'item</a>
            </form>
        <?php } ?>

    </main>

    <!--  FOOTER  -->
    <?php include("inc/fragments/footer.inc.php"); ?>

    <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>

    <script>window.jQuery || document.write('<script src="node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>
    <script src="js/menu.js"></script>
    <script>
        $('body').addClass('js');
        /**
         * Initialiser les modules JavaScript ici: menu, accordéon...
         */
        $(document).ready(menu.initialiser.bind(menu));
    </script>
</body>
</html>