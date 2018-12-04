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
    $strCodeErreur=00000;
    //Initialisation de la variable du champ
    $strChamp="";
    //Initialisation de la variable du message d'erreur
    $strMessageErreur="";


    //Vérifications de la présence de l'id dans la querystring
    if(isset($_GET['id_liste'])){
        $strIdListe=$_GET['id_liste'];
    }

    //Vérifications de la présence du code d'opération Modifier
    if(isset($_GET['codeOperation'])){
        $strCodeOperation=$_GET['codeOperation'];
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
    if($strCodeOperation=='Add'){
        //Récupération des données dans la querystring
        $arrListe['nom_liste']=$_GET['nomListe'];
        if(isset($_GET['couleur'])){
            $arrListe['id_couleur']=$_GET['couleur'];
        }
        else{
            $arrListe['id_couleur']=0;
        }
        //****************************************************************
        //VÉRIFICATIONS DES MODIFS & GESTION DES ERREURS
        //****************************************************************
        //Vérifications du contenu du contenu du nom de la liste
        if(!preg_match('/^[a-zA-ZÀ-ÿ1-9\'\-#]{1,50}$/', $arrListe['nom_liste'])){
            //Si nom du participant est invalide
            $strCodeErreur="-1";
        }
        if($arrListe['id_couleur']==0){
            
        }

        if($strCodeOperation=="Add" && $strCodeErreur==00000){
            //Requête SQL utilisée pour les modifications de la BD
            $strRequeteAdd=
            'INSERT INTO t_liste (nom_liste, id_couleur, id_utilisateur)'.
            ' VALUES (:nom_liste, :id_couleur, :id_utilisateur)';
            
            //Préparation de la requête
            $pdosResultatAdd=$pdoConnexion->prepare($strRequeteAdd);

            //Liaison des valeurs
            $pdosResultatAdd->bindValue('nom_liste', $arrListe['nom_liste']);
            $pdosResultatAdd->bindValue('id_couleur', $arrListe['id_couleur']);
            $pdosResultatAdd->bindValue('id_utilisateur', $strUtilisateur);
            
            //Éxécution de la requête
            $pdosResultatAdd->execute();

            //Récupération de l'erreur, s'il y a lieu
            $strCodeErreur=$pdosResultatAdd->errorCode();
            // var_dump($pdosResultatUpdate->errorInfo());

            //Redirection vers l'index
            header("Location:".$strNiveau."index.php?codeOperation=".$strCodeOperation);
        }
        else{
            if($arrListe['nom_liste']==""){
                $strMessageErreur=$jsonMessagesErreurs->{'nom_liste'}->{'erreurs'}->{'vide'};
            }
            else{
                $strMessageErreur=$jsonMessagesErreurs->{'nom_liste'}->{'erreurs'}->{'motif'};
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Never4get - Ajouter une liste</title>
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

        <h1>Ajouter une liste</h1>
        <?php 
            if($strMessageErreur!=""){ ?>
                <p class="erreur"><?php echo $strMessageErreur; ?></p>
        <?php } ?>

        <?php include($strNiveau . "inc/fragments/sideNav.inc.php"); ?>

        <form action="ajouter-liste.php" method="GET">
            <input type="hidden" name="id_liste" value="<?php echo $arrListe['id_liste']; ?>">

            <div class="conteneurChamp">
                <label for="nomListe">Nom de la liste</label>
                <input type="text" id="nomListe" value="" pattern="[a-zA-ZÀ-ÿ1-9 -'#]{1,55}" name="nomListe">
            </div>

            <fieldset class="conteneurChamp">
                <legend>Changement de couleurs <span class="erreur"><?php echo $arrMessagesErreur['couleurs']; ?></span></legend>
                <ul>
                    <?php
                        for($intCpt=0;$intCpt<count($arrCouleurs);$intCpt++){ ?>
                            <li>
                                <input type="radio" value="<?php echo $arrCouleurs[$intCpt]['id_couleur']; ?>" name="couleur">
                                <?php echo $arrCouleurs[$intCpt]['nom_couleur_fr']; ?>
                                <span style="display: inline-block;width:20px; height: 10px; background-color: #<?php echo $arrCouleurs[$intCpt]['hexadecimale']; ?>;"></span>
                            </li>
                    <?php } ?>    
                </ul>
            </fieldset>

            <button type="submit" value="Add" name="codeOperation">Ajouter</button>
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