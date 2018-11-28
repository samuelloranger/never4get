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

//Inclusion de la config liant aux BD
include ($strNiveau.'inc/scripts/config.inc.php');

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
    WHERE id_liste='.$arrListes[$intCpt]['id_liste'];

    //Éxécution de la requête
    $pdosResultatNbItems=$pdoConnexion->query($strRequeteNbItems);

    //Count des résultats 
    $arrListes[$intCpt]['nbItems']=$pdosResultatNbItems->rowCount();

    // var_dump($nbItems);
    $pdosResultatNbItems->closeCursor();
    
}
$pdosResultatListes->closeCursor();

//**************ÉCHÉANCES À VENIR**************/
//Requête SQL
$strRequeteEcheances=
'SELECT DISTINCT nom_item, echeance, hexadecimale
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
        <a href="#contenu" class="visuallyHidden">Allez au contenu</a>
        <noscript>
            <p>Le JavaScript n'est pas activé dans votre navigateur. Nous vous recommandons de l'activer afin d'améliorer votre expérience utilisateur.</p>
        </noscript>
        <div class="echeancesBandeau conteneur">
            <div class="echeancesBandeau__notif">
                <p>Je désire rec</p>
            <?php 
                for($intCpt=0;$intCpt<count($arrEcheances);$intCpt++){ ?>
                    <p>
                        <span style="display: inline-block;width:20px; height: 20px; background-color: #<?php echo $arrEcheances[$intCpt]['hexadecimale']; ?>;"></span>
                        <?php echo $arrEcheances[$intCpt]['nom_item']; ?> / <?php echo $arrEcheances[$intCpt]['echeance']; ?>
                    </p>
            <?php } ?> 
        </div>
        <div class="allLists conteneur">
            <?php 
                for($intCpt=0;$intCpt<count($arrListes);$intCpt++){ ?>
                <ul>
                    <li><?php echo $arrListes[$intCpt]['id_liste']; ?></li>
                    <li>
                        <a href="editer-liste.php?idListe=<?php echo $arrListes[$intCpt]['id_liste']; ?>">
                            <?php echo $arrListes[$intCpt]['nom_liste']; ?>
                        </a>
                    </li>
                    <li>
                        <?php echo $arrListes[$intCpt]['hexadecimale']; ?>
                    </li>
                    <li>
                        <?php echo $arrListes[$intCpt]['nbItems']; ?>
                    </li>
                </ul>
                <form action="index.php">
                    <input type="hidden" name="id_liste" value="<?php echo $arrListes[$intCpt]['id_liste']; ?>">
                    <a href="editer-liste.php?id_liste=<?php echo $arrListes[$intCpt]['id_liste']; ?>">Éditer la liste</a>
                    <a href="consulter-liste.php?id_liste=<?php echo $arrListes[$intCpt]['id_liste']; ?>">Consulter</a>
                    <button>Supprimer</button>
                </form>
            <?php } ?>
        </div>
    </main>

    <?php include($strNiveau.'inc/fragments/footer.inc.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>

    <script>window.jQuery || document.write('<script src="node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>

    <script src="js/menu.js"></script>

    <script>
    var niveau = "<?php echo $strNiveau; ?>"    
    $('body').addClass('js');
    $(document).ready(function()
    {
        /**
         *Initialiser les modules JavaScript ici: menu, accordéon...
         */
        $(document).ready(menu.initialiser.bind(menu));
    });
    </script>
</body>
</html>