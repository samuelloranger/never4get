<?php
    //*******DÉCLARATION DES VARIABLES*******/
    //Initialisation de la variable niveau
    $strNiveau = "./";
    //Initialisation de la variable du tableau des couleurs
    $arrListe=array();
    //Initialisation de la variable du tableau des couleurs
    $arrCouleurs=array();
    //Initialisation de la variable de l'utilisateur (HARD CODER POUR PROTOTYPE)
    $strUtilisateur=1;
    //Initialisation de la variable contenant l'id de la liste
    $strIdListe="";
    //Initialisation de la variable contenant le code d'opération
    $strCodeOperation="";
    //Initialisation de la variable contenant le code d'erreur
    $strCodeErreur=0000;
    //Initialisation de la variable contenant le code d'erreur
    $strMessage="";


    //Vérifications de la présence de l'id dans la querystring
    if(isset($_GET['id_liste'])){
        $strIdListe=$_GET['id_liste'];
    }

    //Vérifications de la présence du code d'opération Modifier
    if(isset($_GET['btn_modifier'])){
        $strCodeOperation="modifier";
    }

    //Inclusion de la config liant aux BD
    include ($strNiveau.'inc/scripts/config.inc.php');

    //**************ARRAYS DES ERREURS**************/
    //Récupération du fichier JSON contenant les messages d'erreurs
    $strFichierErreurs=file_get_contents($strNiveau.'js/objJSONMessages.json');
    $jsonMessagesErreurs=json_decode($strFichierErreurs);

    //Lister les champs fautifs et leur message
    $arrChampsErreur=array();
    $arrMessagesErreur=array();
    $arrMessagesErreur['nom_liste']="";
    $arrMessagesErreur['couleurs']="";


    //**************DONNÉES DE LA LISTE**************/
    $strRequeteListe=
    'SELECT id_liste, nom_liste, t_couleur.id_couleur, nom_couleur_fr, hexadecimale
    FROM t_liste
    INNER JOIN t_couleur
    ON t_liste.id_couleur=t_couleur.id_couleur
    WHERE id_liste=?';

    //Préparation de la requête
    $pdosResultatListe=$pdoConnexion->prepare($strRequeteListe);

    //Liaison de la valeur de l'id_liste
    $pdosResultatListe->bindValue(1, $strIdListe);

    $pdosResultatListe->execute();

    //Vérification d'erreur
    $strCodeErreur=$pdosResultatListe->errorCode();

    $ligne=$pdosResultatListe->fetch();
    $arrListe['id_liste']=$ligne['id_liste'];
    $arrListe['nom_liste']=$ligne['nom_liste'];
    $arrListe['id_couleur']=$ligne['id_couleur'];
    $arrListe['nom_couleur_fr']=$ligne['nom_couleur_fr'];
    $arrListe['hexadecimale']=$ligne['hexadecimale'];

    //**************DONNÉES SUR LES COULEURS**************/
    $strRequeteCouleurs=
    'SELECT id_couleur, nom_couleur_fr, hexadecimale
    FROM t_couleur';

    //Éxécution de la requête
    $pdosResultatCouleurs=$pdoConnexion->query($strRequeteCouleurs);

    for($intCpt=0;$ligne=$pdosResultatCouleurs->fetch();$intCpt++){
        $arrCouleurs[$intCpt]['id_couleur']=$ligne['id_couleur'];
        $arrCouleurs[$intCpt]['nom_couleur_fr']=$ligne['nom_couleur_fr'];
        $arrCouleurs[$intCpt]['hexadecimale']=$ligne['hexadecimale'];
    }

    //Vérification d'erreur
    $strCodeErreur=$pdosResultatCouleurs->errorCode();

    $pdosResultatCouleurs->closeCursor();

    //**************MODIFICATION DE LA LISTE**************/
    if($strCodeOperation=='modifier'){
        //Récupération des données dans la querystring
        $arrListe['nom_liste']=$_GET['nomListe'];
        $arrListe['id_couleur']=$_GET['couleur'];


        //Requête SQL utilisée pour les modifications de la BD
        $strRequeteUpdate=
        'UPDATE t_liste SET '.
        "nom_liste='".$arrListe['nom_liste']."',".
        "id_couleur='".$arrListe['id_couleur']."'".
        " WHERE id_liste=?";

        //Préparation de la requête
        $pdosResultatUpdate=$pdoConnexion->prepare($strRequeteUpdate);

        //Liaison de la valeur de l'id
        $pdosResultatUpdate->bindValue(1, $strIdListe); 
        
        //Éxécution de la requête
        $pdosResultatUpdate->execute();

        //Récupération de l'erreur, s'il y a lieu
        $strCodeErreur=$pdosResultatUpdate->errorCode();
        // var_dump($pdosResultatUpdate->errorInfo());

        //Redirection vers l'index
        header("Location:".$strNiveau."index.php?strCodeOperation=".$strCodeOperation);

    }
    //****************************************************************
    //VÉRIFICATIONS DES MODIFS & GESTION DES ERREURS
    //****************************************************************


    // S'il y a une erreur
    if($strCodeErreur!='0000'){
        // Remplacer le message par un message d'erreur
        $strMessage=$jsonMessagesErreurs->{$champ}->{'erreurs'}->{$motif};

        array_push($arrChampsErreur, ['nom_liste', 'motif']);
        // for($intCpt=0;$intCpt<count($arrChampsErreur);$intCpt++){
        //     $champ=$arrChampsErreur[$intCpt];
        //     $arrMessagesErreur[$champ]=$jsonMessagesErreurs->{$champ};
        // }
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Projet TOFU</title>
    <!--URL de base pour la navigation -->
    <link rel="stylesheet" href="css/styles.css">
    <?php include($strNiveau . "inc/scripts/headlinks.php"); ?>
</head>
<body>
    <?php include($strNiveau.'inc/fragments/header.inc.php'); ?>
    <!--http://webaim.org/techniques/skipnav/-->
    <a class="visuallyHidden" href="#contenu">Allez au contenu</a>

    <main class="conteneur">
        <noscript>
            <p>Le JavaScript n'est pas activé dans votre navigateur. Nous vous recommandons de l'activer afin d'améliorer votre expérience utilisateur.</p>
        </noscript>

        <h1>Éditer une liste</h1>

        <?php include($strNiveau . "inc/fragments/sideNav.inc.php"); ?>

        <form action="editer-liste.php" method="GET">
            <input type="hidden" name="id_liste" value="<?php echo $arrListe['id_liste']; ?>">

            <div class="conteneurChamp">
                <label for="nomListe">Nom de la liste</label>
                <input type="text" id="nomListe" value="<?php echo $arrListe['nom_liste']; ?>" pattern="[a-zA-ZÀ-ÿ1-9 -'#]{1,55}"name="nomListe">
                <p class="erreur"><?php echo $arrMessagesErreur['nom_liste']; ?></p>
            </div>

            <fieldset class="conteneurChamp">
                <legend>Changement de couleurs <span class="erreur"><?php echo $arrMessagesErreur['nom_liste']; ?></span></legend>
                <ul>
                    <?php
                        for($intCpt=0;$intCpt<count($arrCouleurs);$intCpt++){ ?>
                            <li>
                                <input type="radio" id="couleur" value="<?php echo $arrCouleurs[$intCpt]['id_couleur']; ?>" <?php if($arrCouleurs[$intCpt]['nom_couleur_fr']==$arrListe['nom_couleur_fr']){ ?> checked="checked" <?php } ?> name="couleur">
                                <?php echo $arrCouleurs[$intCpt]['nom_couleur_fr']; ?>
                                <span style="display: inline-block;width:20px; height: 10px; background-color: #<?php echo $arrCouleurs[$intCpt]['hexadecimale']; ?>;"></span>
                            </li>
                    <?php } ?>    
                </ul>
            </fieldset>

            <button type="submit" value="Modifier" name="btn_modifier">Modifier</button>
            <a href="index.php">Annuler</a>
        </form>
    </main>




    <?php include($strNiveau.'inc/fragments/footer.inc.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>

    <script>window.jQuery || document.write('<script src="node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>

    <script src="js/validationsMandatA.js"></script>
    <script src="js/menu.js"></script>

    <script>
    var niveau = "<?php echo $strNiveau; ?>"    

    $('body').addClass('js');
    $(document).ready(function()
    {
        /**
         *Initialiser les modules JavaScript ici: menu, accordéon...
         */
        $(document).ready(validationsMandatA.initialiser.bind(validationsMandatA));

        $(document).ready(menu.initialiser.bind(menu));
    });
    </script>
</body>
</html>