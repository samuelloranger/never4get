<?php
//*******DÉCLARATION DES VARIABLES*******/
//Initialisation de la variable niveau
$strNiveau = "./";
//Initialisation de la variable du tableau de listes
$arrListes=array();
//Initialisation de la variable du tableay des échéances à venir
$arrEcheances=array();
//Initialisation de la variable de l'utilisateur (HARD CODER POUR PROTOTYPE)
$strUtilisateur=1;
//Initilisation de la variable contenant la rétroaction de l'accueil
$strMessageRetro="";

//Récupération du fichier JSON contenant les messages de rétroactions
$strFichierErreurs=file_get_contents($strNiveau.'js/objJSONMessages.json');
$jsonMessagesErreurs=json_decode($strFichierErreurs);

//**************GESTION DES MESSAGES DE RÉTROACTIONS**************/
if(isset($_GET['codeOperation'])){
    if($_GET['codeOperation']=="Add"){
        $strMessageRetro=$jsonMessagesErreurs->{"retroactions"}->{"liste"}->{"ajouter"};
    }    
    else{
        $strMessageRetro=$jsonMessagesErreurs->{"retroactions"}->{"liste"}->{"modifier"};
    }
}

//Inclusion de la config liant aux BD
include ($strNiveau.'inc/scripts/config.inc.php');

//**************SUPPRESSION DE LISTE**************/
if(isset($_GET['supprimerListe'])){
    //Initialisation de la valeur de la liste à supprimer
    $strIdListeDelete=$_GET['supprimerListe'];

    //Requête SQL
    $strRequeteDelete=
    'DELETE from t_liste
    WHERE id_liste=?';

    //Éxécution de la requête
    $pdosResultatDelete=$pdoConnexion->prepare($strRequeteDelete);

    //Récupération de l'erreur, s'il y a lieu
    $strCodeErreur=$pdosResultatDelete->errorCode();
    // var_dump($pdosResultatSupp->errorInfo());

    //Liaison de la valeur de l'id
    $pdosResultatDelete->bindValue(1, $strIdListeDelete);

    //Éxécution de la requête
    $pdosResultatDelete->execute();

    //Message de rétroaction
    $strMessageRetro=$jsonMessagesErreurs->{"retroactions"}->{"liste"}->{"supprimer"};
}
//**************ÉCHÉANCES À VENIR**************/
//Requête SQL
$strRequeteEcheances=
'SELECT nom_item, echeance, hexadecimale
FROM t_item
INNER JOIN t_liste ON t_item.id_liste=t_liste.id_liste
INNER JOIN t_couleur ON t_liste.id_couleur=t_couleur.id_couleur
WHERE echeance IS NOT NULL 
ORDER BY nom_item, echeance ASC
LIMIT 3';

//Éxécution de la requête
$pdosResultatEcheances=$pdoConnexion->query($strRequeteEcheances);

//Formation du array contenant les données des échéances
for($intCpt=0;$ligne=$pdosResultatEcheances->fetch();$intCpt++){
    $arrEcheances[$intCpt]['nom_item']=$ligne['nom_item'];
    $arrEcheances[$intCpt]['echeance']=$ligne['echeance'];
    $arrEcheances[$intCpt]['hexadecimale']=$ligne['hexadecimale'];
}

$pdosResultatEcheances->closeCursor();


//**************CLASSEMENT DES LISTES**************/
//Requête SQL
$strRequeteListes=
'SELECT id_liste, nom_liste, hexadecimale
FROM t_liste
INNER JOIN t_couleur ON t_liste.id_couleur=t_couleur.id_couleur
WHERE id_utilisateur=? 
ORDER BY nom_liste';

//Préparation la requête
$pdosResultatListes=$pdoConnexion->prepare($strRequeteListes);

//Liaison de la valeur de l'utilisateur
$pdosResultatListes->bindValue(1, $strUtilisateur);

//Éxécution de la requête
$pdosResultatListes->execute();

//Extraction des données et création du array
for($intCpt=0;$ligne=$pdosResultatListes->fetch();$intCpt++){
    $arrListes[$intCpt]['id_liste']=$ligne['id_liste'];
    $arrListes[$intCpt]['nom_liste']=$ligne['nom_liste'];
    $arrListes[$intCpt]['hexadecimale']=$ligne['hexadecimale'];
    $arrListes[$intCpt]['nbItems']="";

    //**************ITEMS LIÉS AUX LISTES**************/
    //Requête SQL
    $strRequeteNbItems=
    'SELECT id_item
    FROM t_item
    WHERE id_liste=?';

    //Éxécution de la requête
    $pdosResultatNbItems=$pdoConnexion->prepare($strRequeteNbItems);
    $pdosResultatNbItems->bindValue(1,$arrListes[$intCpt]['id_liste']);
    $pdosResultatNbItems->execute();
    //Count des résultats 
    $arrListes[$intCpt]['nbItems']=$pdosResultatNbItems->rowCount();

    $pdosResultatNbItems->closeCursor();
    
}
$pdosResultatListes->closeCursor();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Accueil-Projet TOFU</title>
    <!--URL de base pour la navigation -->
    <base href="<?php echo $strNiveau ?>">
    <link rel="stylesheet" href="css/styles.css">
    <?php include($strNiveau . "inc/scripts/headlinks.php"); ?>
</head>
<body>
    <?php include($strNiveau.'inc/fragments/header.inc.php'); ?>
    <!--http://webaim.org/techniques/skipnav/-->
    <main>
        <a href="#contenu" class="visuallyHidden focusable">Allez au contenu</a>
        <noscript>
            <p>Le JavaScript n'est pas activé dans votre navigateur. Nous vous recommandons de l'activer afin d'améliorer votre expérience utilisateur.</p>
        </noscript>
        <div class="echeancesBandeau conteneur">
            <div class="echeancesBandeau__dates">
            <?php 
                for($intCpt=0;$intCpt<count($arrEcheances);$intCpt++){ ?>
                    <p>
                        <span style="display: inline-block;width:20px; height: 20px; background-color: #<?php echo $arrEcheances[$intCpt]['hexadecimale']; ?>;"></span>
                        <?php echo $arrEcheances[$intCpt]['nom_item']; ?> / <?php echo $arrEcheances[$intCpt]['echeance']; ?>
                    </p>
            <?php } ?> 
            </div>
            <div class="echeancesBandeau__notifs">
                <label class="echeancesBandeau__notifsTexte">
                    <span class="echeancesBandeau__notifsIcon fi flaticon-alarm">
                        <input type="checkbox" class="visuallyHidden">
                    </span>
                        Je désire recevoir notifications des échéances
                </label>
            </div> 
        </div>
        <div class="allLists conteneur">
            <div class="allLists__intro">
                <h1 class="allLists__introTitre">Listes</h1>
                <a href="ajouter-liste.php" class="btnOperation fi flaticon-add">Ajouter une liste</a>
            </div>
            <div class="allLists__containerFlex">
                <!--Intégration du message de rétroaction-->
                <?php
                    //Si le message a été changé
                    if($strMessageRetro!=""){ ?>
                        <p class="allLists__retroactions">
                            <?php echo $strMessageRetro; ?>
                        </p>
                <?php } ?>

                <!--Intégration des listes présentes dans l'array créé plus tôt-->
                <?php 
                    for($intCpt=0;$intCpt<count($arrListes);$intCpt++){ ?>
                    <article class="allLists__itemList">
                        <form action="index.php">
                            <input type="hidden" name="id_liste" value="<?php echo $arrListes[$intCpt]['id_liste']; ?>">
                            <header style="background-color: #<?php echo $arrListes[$intCpt]['hexadecimale']; ?>">
                                <p class="allLists__itemListNb">
                                    <?php echo $arrListes[$intCpt]['nbItems']; ?>
                                </p>
                            </header> 
                            <div class="allLists__itemListContent">
                                <h2 class="allLists__itemListNom">
                                    <a href="editer-liste.php?idListe=<?php echo $arrListes[$intCpt]['id_liste']; ?>">
                                        <?php echo $arrListes[$intCpt]['nom_liste']; ?>
                                    </a>
                                </h2>

                                <a href="consulter-liste.php?id_liste=<?php echo $arrListes[$intCpt]['id_liste']; ?>" class="fi flaticon-more">Consulter</a>
                                
                                <a href="editer-liste.php?id_liste=<?php echo $arrListes[$intCpt]['id_liste']; ?>" class="fi flaticon-edit">Éditer la liste</a>

                                <a href="index.php#modalDelete<?php echo $arrListes[$intCpt]['id_liste']; ?>" class="fi flaticon-trash allLists__itemListContent--ecard">Supprimer</a>
                            </div>
                            <!--Modal Box utilisé pour la suppression des liste-->
                            <div id="modalDelete<?php echo $arrListes[$intCpt]['id_liste']; ?>" class="modalBox">
                                <div class="modalBox__dialogue">
                                    <div class="modalBox__fenetre">
                                        <header class="modalBox__entete" style="background-color: #<?php echo $arrListes[$intCpt]['hexadecimale']; ?>">
                                        </header>
                                        <a href="index.php#" class="btn btn--fermer">Fermer</a>
                                        <div class="modalBox__contenu">
                                            <p><strong>Voulez-vous vraiment supprimer la liste <?php echo $arrListes[$intCpt]['nom_liste']; ?> et tout ce qu'elle contient?</strong></p>

                                        </div>
                                        <footer class="modalBox__actions">
                                            <a href="index.php#" class="btn">Annuler</a>&nbsp;&nbsp;&nbsp;
                                            <button type="submit" name="supprimerListe" class="btn btn--danger" value="<?php echo $arrListes[$intCpt]['id_liste']; ?>">Supprimer la liste </button>
                                        </footer>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </article>
                <?php } ?>
            </div>
        </div>
    </main>

    <?php include($strNiveau.'inc/fragments/footer.inc.php'); ?>

    <?php include($strNiveau.'inc/scripts/footerLinks.inc.php'); ?>
    </script>
</body>
</html>