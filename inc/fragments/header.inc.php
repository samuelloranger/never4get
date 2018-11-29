<?php

// *************************** Liste des listes **********************
$strRequeteListes = "SELECT id_liste, nom_liste FROM t_liste";

$pdosResultatListes = $pdoConnexion -> query($strRequeteListes);

$arrListesHeader = array();
for($intCtr = 0; $ligne = $pdosResultatListes -> fetch(); $intCtr++){
    $arrListes[$intCtr]["id_liste"] = $ligne["id_liste"];
    $arrListes[$intCtr]["nom_liste"] = $ligne["nom_liste"];
}

$pdosResultatListes -> closeCursor();
?>

<header class="header bleed">
    <div class="conteneur">
        <div class="header__logo">
            <a href="index.php" tabindex="-1">
                <picture>
                    <source srcset="images/logo/icon_never4get_x1200.png" media="(min-width: 601px)">
                    <img  src="images/logo/icon_never4get_x40.png" alt="logo">
                </picture>
            </a>
        </div>

        <div class="contenuTable">
            <div class="contenuTable__profil fi flaticon-user">
                <span>Mon profil</span>
            </div>
            <div class="contenuTable__recherche">
                <input type="text" placeholder="Recherche">
            </div>
        </div>

        <ul class="header__liste header__liste--ferme">
            <div class="header__listeContainer">
                <li class="header__listeItem"><a class="fi flaticon-home" href="index.php">Accueil</a></li>
                <li class="header__listeItem"><a class="fi flaticon-add"  href="ajouter-liste.php">Ajouter une liste</a></li>
                <li class="header__listeItem"><a  class="fi flaticon-user" href="">Compte</a></li>
                <?php for($intCtr = 0; $intCtr < count($arrListes); $intCtr++){?>
                    <li class="header__listeItem  <?php if(isset($_GET["id_item"]) == true){  if($arrListes[$intCtr]["id_liste"] == $arrInfosItem["id_liste"]) { echo "header__listeItem--active"; } } else{ if($arrListes[$intCtr]["id_liste"] == $arrInfosListe["id_liste"]) { echo "header__listeItem--active"; } }?>">
                        <a href= consulter-liste.php?id_liste=<?php echo  $arrListes[$intCtr]["id_liste"] ?>"><?php echo $arrListes[$intCtr]["nom_liste"]; ?></a>
                    </li>
                <?php } ?>
            </div>
        </ul>
    </div>
</header>