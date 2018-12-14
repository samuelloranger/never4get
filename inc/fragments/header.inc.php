<?php

// *************************** Liste des listes **********************
$strRequeteListesHeader = "SELECT id_liste, nom_liste FROM t_liste";

$pdosResultatListesHeader = $pdoConnexion -> query($strRequeteListesHeader);

$arrListesHeader = array();
for($intCtr = 0; $ligne = $pdosResultatListesHeader -> fetch(); $intCtr++){
    $arrListesHeader[$intCtr]["id_liste"] = $ligne["id_liste"];
    $arrListesHeader[$intCtr]["nom_liste"] = $ligne["nom_liste"];
}

$pdosResultatListesHeader -> closeCursor();
?>

<header class="header bleed">
    <div class="conteneur">
        <div class="header__logo">
            <a href="index.php" tabindex="-1">
                <picture>
                    <source srcset="images/logo/icon_never4get_x1200.png" media="(min-width: 600px)">
                    <img  src="images/logo/icon_never4get_x40.png" alt="logo">
                </picture>
            </a>
        </div>

        <div class="contenuTable">
            <div class="contenuTable__profil fi flaticon-user">
                <a href="#profilContenu" class="contenuTable__profilLink"><span class="contenuTable__profilLibelle">Mon profil</span></a>
                <div id="profilContenu">
                    <a href="#">Paramètres</a>
                    <a href="#">Déconnexion</a>
                </div>
            </div>
            <div class="contenuTable__recherche">
                <a href="#recherche"></a>
                <input type="text" placeholder="Recherche">
            </div>
        </div>

        <ul class="header__liste header__liste--ferme">
            <div class="header__listeContainer">
                <li class="header__listeItem"><a class="fi flaticon-home" href="index.php">Accueil</a></li>
                <li class="header__listeItem"><a class="fi flaticon-add"  href="ajouter-liste.php">Ajouter une liste</a></li>
                <li class="header__listeItem"><a  class="fi flaticon-userMobile" href="">Compte</a></li>
                <?php for($intCtr = 0; $intCtr < count($arrListesHeader); $intCtr++){?>
                    <li class="header__listeItem  <?php if(isset($_GET["id_item"]) == true){  if($arrListesHeader[$intCtr]["id_liste"] == $arrInfosItem["id_liste"]) { echo "header__listeItem--active"; } } else{ if($arrListesHeader[$intCtr]["id_liste"] == $arrInfosListe["id_liste"]) { echo "header__listeItem--active"; } }?>">
                        <a href="consulter-liste.php?id_liste=<?php echo  $arrListesHeader[$intCtr]["id_liste"] ?>"><?php echo $arrListesHeader[$intCtr]["nom_liste"]; ?></a>
                    </li>
                <?php } ?>
            </div>
        </ul>
    </div>
</header>