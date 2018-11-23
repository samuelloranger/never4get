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

    //Vérifications de la présence de l'id dans la querystring
    if(isset($_GET['id_liste'])){
        $strIdListe=$_GET['id_liste'];
    }
    //Pour test
    else{
        $strIdListe=1;
    }

    var_dump($strIdListe);

    //Inclusion de la config liant aux BD
    include ($strNiveau.'inc/scripts/config.inc.php');


    //**************DONNÉES DE LA LISTE**************/
    $strRequeteListe=
    'SELECT nom_liste, t_couleur.id_couleur, nom_couleur_fr, hexadecimale
    FROM t_liste
    INNER JOIN t_couleur
    ON t_liste.id_couleur=t_couleur.id_couleur
    WHERE id_liste=?';

    //Préparation de la requête
    $pdosResultatListe=$pdoConnexion->prepare($strRequeteListe);

    //Liaison de la valeur de l'id_liste
    $pdosResultatListe->bindValue(1, $strIdListe);

    $pdosResultatListe->execute();

    $ligne=$pdosResultatListe->fetch();
    $arrListe['nom_liste']=$ligne['nom_liste'];
    $arrListe['id_couleur']=$ligne['id_couleur'];
    $arrListe['nom_couleur_fr']=$ligne['nom_couleur_fr'];
    $arrListe['hexadecimale']=$ligne['hexadecimale'];


    //**************DONNÉES SUR LES COULEURS**************/
    $strRequeteCouleurs=
    'SELECT nom_couleur_fr, hexadecimale
    FROM t_couleur';

    //Éxécution de la requête
    $pdosResultatCouleurs=$pdoConnexion->query($strRequeteCouleurs);

    for($intCpt=0;$ligne=$pdosResultatCouleurs->fetch();$intCpt++){
        $arrCouleurs[$intCpt]['nom_couleur_fr']=$ligne['nom_couleur_fr'];
        $arrCouleurs[$intCpt]['hexadecimale']=$ligne['hexadecimale'];
    }
    $pdosResultatCouleurs->closeCursor();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width"/>
    <title>Projet TOFU</title>
    <!--URL de base pour la navigation -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include($strNiveau.'inc/fragments/header.inc.php'); ?>
    <!--http://webaim.org/techniques/skipnav/-->
    <a href="#contenu">Allez au contenu</a>

    <main>
        <noscript>
            <p>Le JavaScript n'est pas activé dans votre navigateur. Nous vous recommandons de l'activer afin d'améliorer votre expérience utilisateur.</p>
        </noscript>

        <h1>Éditer une liste</h1>
        <label for="nomListe">Nom de la liste</label>
        <input type="text" value="<?php echo $arrListe['nom_liste']; ?>">

        <fieldset>
            <legend>Changement de couleurs</legend>
            <ul>
                <?php
                    for($intCpt=0;$intCpt<count($arrCouleurs);$intCpt++){ ?>
                        <li>
                            <input type="radio" value="<?php echo $arrCouleurs[$intCpt]['hexadecimale']; ?>">
                            <?php echo $arrCouleurs[$intCpt]['nom_couleur_fr']; ?>
                            <span style="display: inline-block;width:20px; height: 10px; background-color: #<?php echo $arrCouleurs[$intCpt]['hexadecimale']; ?>;"></span>
                        </li>
                <?php } ?>    
            </ul>
        </fieldset>
    </main>




    <?php include($strNiveau.'inc/fragments/footer.inc.php'); ?>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"
    integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
    crossorigin="anonymous"></script>

    <script>window.jQuery || document.write('<script src="node_modules/jquery/dist/jquery.min.js">\x3C/script>')</script>

    <script>
    $('body').addClass('js');
    $(document).ready(function()
    {
        /**
         *Initialiser les modules JavaScript ici: menu, accordéon...
         */
    });
    </script>
</body>
</html>